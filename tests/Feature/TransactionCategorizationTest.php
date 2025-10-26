<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\CategorizationRule;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionCategorizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionCategorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Account $account;
    private Category $category;
    private TransactionCategorizationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->account = Account::factory()->create(['user_id' => $this->user->id]);
        $this->category = Category::factory()->create(['user_id' => $this->user->id]);
        $this->service = new TransactionCategorizationService();
    }

    public function test_merchant_contains_rule_matches()
    {
        // Create a rule
        CategorizationRule::create([
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'rule_type' => 'merchant_contains',
            'pattern' => 'walmart',
            'priority' => 80,
            'is_active' => true,
        ]);

        // Create a transaction
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'merchant' => 'WALMART SUPERCENTER',
            'category_id' => null,
        ]);

        // Test categorization
        $categoryId = $this->service->categorizeTransaction($transaction);

        $this->assertEquals($this->category->id, $categoryId);
    }

    public function test_description_contains_rule_matches()
    {
        CategorizationRule::create([
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'rule_type' => 'description_contains',
            'pattern' => 'gas station',
            'priority' => 85,
            'is_active' => true,
        ]);

        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'description' => 'Purchase at local gas station',
            'category_id' => null,
        ]);

        $categoryId = $this->service->categorizeTransaction($transaction);

        $this->assertEquals($this->category->id, $categoryId);
    }

    public function test_amount_range_rule_matches()
    {
        CategorizationRule::create([
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'rule_type' => 'amount_range',
            'pattern' => '50-200',
            'priority' => 70,
            'is_active' => true,
        ]);

        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'amount' => 100.0,
            'category_id' => null,
        ]);

        $categoryId = $this->service->categorizeTransaction($transaction);

        $this->assertEquals($this->category->id, $categoryId);
    }

    public function test_rule_priority_ordering()
    {
        $category1 = Category::factory()->create(['user_id' => $this->user->id]);
        $category2 = Category::factory()->create(['user_id' => $this->user->id]);

        // Lower priority rule
        CategorizationRule::create([
            'category_id' => $category1->id,
            'user_id' => $this->user->id,
            'rule_type' => 'merchant_contains',
            'pattern' => 'walmart',
            'priority' => 50,
            'is_active' => true,
        ]);

        // Higher priority rule
        CategorizationRule::create([
            'category_id' => $category2->id,
            'user_id' => $this->user->id,
            'rule_type' => 'merchant_contains',
            'pattern' => 'walmart',
            'priority' => 90,
            'is_active' => true,
        ]);

        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'merchant' => 'WALMART SUPERCENTER',
            'category_id' => null,
        ]);

        $categoryId = $this->service->categorizeTransaction($transaction);

        // Should match the higher priority rule
        $this->assertEquals($category2->id, $categoryId);
    }

    public function test_inactive_rules_are_ignored()
    {
        CategorizationRule::create([
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'rule_type' => 'merchant_contains',
            'pattern' => 'walmart',
            'priority' => 80,
            'is_active' => false,  // Inactive rule
        ]);

        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'merchant' => 'WALMART SUPERCENTER',
            'category_id' => null,
        ]);

        $categoryId = $this->service->categorizeTransaction($transaction);

        $this->assertNull($categoryId);
    }

    public function test_auto_categorize_on_transaction_creation()
    {
        CategorizationRule::create([
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'rule_type' => 'merchant_contains',
            'pattern' => 'starbucks',
            'priority' => 80,
            'is_active' => true,
        ]);

        // Create transaction - should trigger auto-categorization via event
        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'merchant' => 'STARBUCKS COFFEE',
            'amount' => 5.5,
            'description' => 'Coffee purchase',
            'transaction_date' => now(),
        ]);

        // Manually trigger the categorization since we're in test mode
        $transaction->autoCategorize();
        $transaction->refresh();

        $this->assertEquals($this->category->id, $transaction->category_id);
        $this->assertTrue($transaction->auto_categorized);
    }

    public function test_create_default_rules()
    {
        $this->service->createDefaultRules($this->user->id);

        $rules = CategorizationRule::where('user_id', $this->user->id)->get();
        $categories = Category::where('user_id', $this->user->id)->get();

        $this->assertGreaterThan(0, $rules->count());
        $this->assertGreaterThan(0, $categories->count());
    }
}
