<?php

namespace App\Models;

use App\Events\TransactionCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'amount',
        'description',
        'merchant',
        'category_id',
        'auto_categorized',
        'transaction_date',
        'external_id',
        'type',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
        'auto_categorized' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'created' => TransactionCreated::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Auto-categorize this transaction based on rules
     */
    public function autoCategorize(): void
    {
        if ($this->auto_categorized || $this->category_id) {
            return;  // Already categorized
        }

        $categoryId = app(TransactionCategorizationService::class)
            ->categorizeTransaction($this);

        if ($categoryId) {
            $this->update([
                'category_id' => $categoryId,
                'auto_categorized' => true
            ]);
        }
    }
}
