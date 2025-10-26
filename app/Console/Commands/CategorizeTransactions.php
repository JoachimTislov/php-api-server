<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\TransactionCategorizationService;
use Illuminate\Console\Command;

class CategorizeTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:categorize 
                            {--user= : Categorize transactions for specific user ID}
                            {--uncategorized-only : Only categorize transactions without categories}
                            {--dry-run : Show what would be categorized without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-categorize transactions based on rules';

    /**
     * Execute the console command.
     */
    public function handle(TransactionCategorizationService $service): int
    {
        $query = Transaction::query();

        // Filter by user if specified
        if ($this->option('user')) {
            $query->where('user_id', $this->option('user'));
        }

        // Filter to uncategorized only if specified
        if ($this->option('uncategorized-only')) {
            $query->whereNull('category_id');
        }

        $transactions = $query->get();

        if ($transactions->isEmpty()) {
            $this->info('No transactions found to categorize.');
            return 0;
        }

        $this->info("Found {$transactions->count()} transactions to process.");

        $categorized = 0;
        $bar = $this->output->createProgressBar($transactions->count());

        foreach ($transactions as $transaction) {
            $categoryId = $service->categorizeTransaction($transaction);

            if ($categoryId) {
                if (!$this->option('dry-run')) {
                    $transaction->update([
                        'category_id' => $categoryId,
                        'auto_categorized' => true
                    ]);
                }
                $categorized++;

                if ($this->option('dry-run')) {
                    $this->line('');
                    $this->line("Would categorize: {$transaction->description} -> Category ID: {$categoryId}");
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->line('');

        if ($this->option('dry-run')) {
            $this->info("Dry run completed. {$categorized} transactions would be categorized.");
        } else {
            $this->info("Categorization completed. {$categorized} transactions were categorized.");
        }

        return 0;
    }
}
