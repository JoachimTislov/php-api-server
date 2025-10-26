<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Services\TransactionCategorizationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AutoCategorizeTransaction implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private TransactionCategorizationService $categorizationService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(TransactionCreated $event): void
    {
        $transaction = $event->transaction;

        // Only auto-categorize if not already categorized
        if ($transaction->category_id === null) {
            $categoryId = $this->categorizationService->categorizeTransaction($transaction);

            if ($categoryId) {
                $transaction->update([
                    'category_id' => $categoryId,
                    'auto_categorized' => true
                ]);
            }
        }
    }
}
