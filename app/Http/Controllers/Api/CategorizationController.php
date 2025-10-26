<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategorizationRule;
use App\Models\Category;
use App\Models\Transaction;
use App\Services\TransactionCategorizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategorizationController extends Controller
{
    public function __construct(
        private TransactionCategorizationService $categorizationService
    ) {}

    /**
     * Auto-categorize a specific transaction
     */
    public function categorizeTransaction(Transaction $transaction): JsonResponse
    {
        $this->authorize('update', $transaction);

        $categoryId = $this->categorizationService->categorizeTransaction($transaction);

        if ($categoryId) {
            $transaction->update([
                'category_id' => $categoryId,
                'auto_categorized' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction categorized successfully',
                'category_id' => $categoryId
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No matching categorization rule found'
        ], 404);
    }

    /**
     * Auto-categorize all uncategorized transactions for the user
     */
    public function categorizeAllTransactions(): JsonResponse
    {
        $user = Auth::user();

        $uncategorizedTransactions = Transaction::where('user_id', $user->id)
            ->whereNull('category_id')
            ->get();

        $results = $this->categorizationService->categorizeTransactions($uncategorizedTransactions);

        // Apply the categorizations
        foreach ($results as $result) {
            Transaction::where('id', $result['transaction_id'])
                ->update([
                    'category_id' => $result['category_id'],
                    'auto_categorized' => true
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Batch categorization completed',
            'categorized_count' => count($results),
            'total_uncategorized' => $uncategorizedTransactions->count()
        ]);
    }

    /**
     * Get categorization rules for the user
     */
    public function getRules(): JsonResponse
    {
        $user = Auth::user();

        $rules = CategorizationRule::with('category')
            ->where('user_id', $user->id)
            ->orderBy('priority', 'desc')
            ->get();

        return response()->json($rules);
    }

    /**
     * Create a new categorization rule
     */
    public function createRule(Request $request): JsonResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'rule_type' => ['required', Rule::in(array_keys(CategorizationRule::RULE_TYPES))],
            'pattern' => 'required|string|max:1000',
            'priority' => 'integer|min:1|max:100',
            'is_active' => 'boolean'
        ]);

        // Verify the category belongs to the user
        $category = Category::where('id', $validated['category_id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$category) {
            return response()->json([
                'error' => 'Category not found or not accessible'
            ], 404);
        }

        $rule = CategorizationRule::create([
            ...$validated,
            'user_id' => $user->id,
            'priority' => $validated['priority'] ?? 50,
            'is_active' => $validated['is_active'] ?? true
        ]);

        return response()->json($rule->load('category'), 201);
    }

    /**
     * Update a categorization rule
     */
    public function updateRule(Request $request, CategorizationRule $rule): JsonResponse
    {
        $this->authorize('update', $rule);

        $validated = $request->validate([
            'category_id' => 'exists:categories,id',
            'rule_type' => Rule::in(array_keys(CategorizationRule::RULE_TYPES)),
            'pattern' => 'string|max:1000',
            'priority' => 'integer|min:1|max:100',
            'is_active' => 'boolean'
        ]);

        // If category_id is being updated, verify it belongs to the user
        if (isset($validated['category_id'])) {
            $category = Category::where('id', $validated['category_id'])
                ->where('user_id', $rule->user_id)
                ->first();

            if (!$category) {
                return response()->json([
                    'error' => 'Category not found or not accessible'
                ], 404);
            }
        }

        $rule->update($validated);

        return response()->json($rule->load('category'));
    }

    /**
     * Delete a categorization rule
     */
    public function deleteRule(CategorizationRule $rule): JsonResponse
    {
        $this->authorize('delete', $rule);

        $rule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rule deleted successfully'
        ]);
    }

    /**
     * Create default categorization rules for the user
     */
    public function createDefaultRules(): JsonResponse
    {
        $user = Auth::user();

        $this->categorizationService->createDefaultRules($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Default categorization rules created'
        ]);
    }

    /**
     * Test a rule pattern against transactions
     */
    public function testRule(Request $request): JsonResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'rule_type' => ['required', Rule::in(array_keys(CategorizationRule::RULE_TYPES))],
            'pattern' => 'required|string|max:1000',
            'limit' => 'integer|min:1|max:100'
        ]);

        $transactions = Transaction::where('user_id', $user->id)
            ->limit($validated['limit'] ?? 20)
            ->get();

        $matches = [];
        $tempRule = new CategorizationRule($validated);

        foreach ($transactions as $transaction) {
            $service = new TransactionCategorizationService();
            $reflection = new \ReflectionClass($service);
            $method = $reflection->getMethod('ruleMatches');
            $method->setAccessible(true);

            if ($method->invoke($service, $tempRule, $transaction)) {
                $matches[] = [
                    'id' => $transaction->id,
                    'description' => $transaction->description,
                    'merchant' => $transaction->merchant,
                    'amount' => $transaction->amount
                ];
            }
        }

        return response()->json([
            'matches' => $matches,
            'total_matches' => count($matches),
            'total_tested' => $transactions->count()
        ]);
    }
}
