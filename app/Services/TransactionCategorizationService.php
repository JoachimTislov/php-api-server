<?php

namespace App\Services;

use App\Models\CategorizationRule;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class TransactionCategorizationService
{
    /**
     * Categorize a transaction based on rules
     */
    public function categorizeTransaction(Transaction $transaction): ?int
    {
        $rules = $this->getApplicableRules($transaction->user_id);

        foreach ($rules as $rule) {
            if ($this->ruleMatches($rule, $transaction)) {
                Log::info('Transaction auto-categorized', [
                    'transaction_id' => $transaction->id,
                    'category_id' => $rule->category_id,
                    'rule_id' => $rule->id,
                    'rule_type' => $rule->rule_type
                ]);

                return $rule->category_id;
            }
        }

        return null;
    }

    /**
     * Categorize multiple transactions in batch
     */
    public function categorizeTransactions(Collection $transactions): array
    {
        $results = [];

        foreach ($transactions as $transaction) {
            $categoryId = $this->categorizeTransaction($transaction);
            if ($categoryId) {
                $results[] = [
                    'transaction_id' => $transaction->id,
                    'category_id' => $categoryId
                ];
            }
        }

        return $results;
    }

    /**
     * Get applicable rules for a user, ordered by priority
     */
    private function getApplicableRules(int $userId): Collection
    {
        return CategorizationRule::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Check if a rule matches a transaction
     */
    private function ruleMatches(CategorizationRule $rule, Transaction $transaction): bool
    {
        return match ($rule->rule_type) {
            'merchant_contains' => $this->merchantContains($rule->pattern, $transaction->merchant),
            'description_contains' => $this->descriptionContains($rule->pattern, $transaction->description),
            'merchant_exact' => $this->merchantExact($rule->pattern, $transaction->merchant),
            'description_exact' => $this->descriptionExact($rule->pattern, $transaction->description),
            'merchant_regex' => $this->merchantRegex($rule->pattern, $transaction->merchant),
            'description_regex' => $this->descriptionRegex($rule->pattern, $transaction->description),
            'amount_range' => $this->amountRange($rule->pattern, $transaction->amount),
            'combined' => $this->combinedRules($rule->pattern, $transaction),
            default => false
        };
    }

    private function merchantContains(string $pattern, ?string $merchant): bool
    {
        if (!$merchant)
            return false;
        return stripos($merchant, $pattern) !== false;
    }

    private function descriptionContains(string $pattern, ?string $description): bool
    {
        if (!$description)
            return false;
        return stripos($description, $pattern) !== false;
    }

    private function merchantExact(string $pattern, ?string $merchant): bool
    {
        if (!$merchant)
            return false;
        return strcasecmp(trim($merchant), trim($pattern)) === 0;
    }

    private function descriptionExact(string $pattern, ?string $description): bool
    {
        if (!$description)
            return false;
        return strcasecmp(trim($description), trim($pattern)) === 0;
    }

    private function merchantRegex(string $pattern, ?string $merchant): bool
    {
        if (!$merchant)
            return false;
        return preg_match('/' . $pattern . '/i', $merchant) === 1;
    }

    private function descriptionRegex(string $pattern, ?string $description): bool
    {
        if (!$description)
            return false;
        return preg_match('/' . $pattern . '/i', $description) === 1;
    }

    private function amountRange(string $pattern, float $amount): bool
    {
        // Pattern format: "min-max" or "min+" or "-max"
        if (str_contains($pattern, '-')) {
            [$min, $max] = explode('-', $pattern, 2);
            $min = $min === '' ? null : (float) $min;
            $max = $max === '' ? null : (float) $max;

            if ($min !== null && $amount < $min)
                return false;
            if ($max !== null && $amount > $max)
                return false;

            return true;
        }

        if (str_ends_with($pattern, '+')) {
            $min = (float) rtrim($pattern, '+');
            return $amount >= $min;
        }

        return false;
    }

    private function combinedRules(string $pattern, Transaction $transaction): bool
    {
        // Pattern is JSON with multiple conditions
        try {
            $rules = json_decode($pattern, true);
            if (!is_array($rules))
                return false;

            foreach ($rules as $rule) {
                $ruleObj = new CategorizationRule($rule);
                if (!$this->ruleMatches($ruleObj, $transaction)) {
                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::warning('Failed to parse combined rule', ['pattern' => $pattern, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Create default categorization rules for a user
     */
    public function createDefaultRules(int $userId): void
    {
        $defaultRules = [
            ['name' => 'Groceries', 'rules' => [
                ['type' => 'merchant_contains', 'pattern' => 'walmart', 'priority' => 80],
                ['type' => 'merchant_contains', 'pattern' => 'target', 'priority' => 80],
                ['type' => 'merchant_contains', 'pattern' => 'kroger', 'priority' => 80],
                ['type' => 'merchant_contains', 'pattern' => 'safeway', 'priority' => 80],
            ]],
            ['name' => 'Gas', 'rules' => [
                ['type' => 'merchant_contains', 'pattern' => 'shell', 'priority' => 90],
                ['type' => 'merchant_contains', 'pattern' => 'chevron', 'priority' => 90],
                ['type' => 'merchant_contains', 'pattern' => 'exxon', 'priority' => 90],
                ['type' => 'description_contains', 'pattern' => 'gas station', 'priority' => 85],
            ]],
            ['name' => 'Restaurants', 'rules' => [
                ['type' => 'merchant_contains', 'pattern' => 'mcdonalds', 'priority' => 85],
                ['type' => 'merchant_contains', 'pattern' => 'starbucks', 'priority' => 85],
                ['type' => 'description_contains', 'pattern' => 'restaurant', 'priority' => 80],
            ]],
            ['name' => 'Entertainment', 'rules' => [
                ['type' => 'merchant_contains', 'pattern' => 'netflix', 'priority' => 95],
                ['type' => 'merchant_contains', 'pattern' => 'spotify', 'priority' => 95],
                ['type' => 'merchant_contains', 'pattern' => 'amazon prime', 'priority' => 95],
            ]],
        ];

        foreach ($defaultRules as $categoryData) {
            $category = Category::firstOrCreate([
                'name' => $categoryData['name'],
                'user_id' => $userId,
            ], [
                'description' => "Auto-created category for {$categoryData['name']}",
                'is_system' => false,
            ]);

            foreach ($categoryData['rules'] as $ruleData) {
                CategorizationRule::create([
                    'category_id' => $category->id,
                    'user_id' => $userId,
                    'rule_type' => $ruleData['type'],
                    'pattern' => $ruleData['pattern'],
                    'priority' => $ruleData['priority'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
