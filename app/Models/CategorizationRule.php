<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CategorizationRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'rule_type',
        'pattern',
        'priority',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public const RULE_TYPES = [
        'merchant_contains' => 'Merchant Contains',
        'description_contains' => 'Description Contains',
        'merchant_exact' => 'Merchant Exact Match',
        'description_exact' => 'Description Exact Match',
        'merchant_regex' => 'Merchant Regex',
        'description_regex' => 'Description Regex',
        'amount_range' => 'Amount Range',
        'combined' => 'Combined Rules'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
