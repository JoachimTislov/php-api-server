<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'categorization_preferences',
        'auto_categorize_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'categorization_preferences' => 'array',
        'auto_categorize_enabled' => 'boolean',
    ];

    /**
     * Get user's categorization preferences
     */
    public function getCategorizationPreferences()
    {
        return $this->categorization_preferences ?? [
            'merchant_patterns' => [],
            'description_keywords' => [],
            'amount_thresholds' => [],
            'learning_enabled' => true,
        ];
    }

    /**
     * Update categorization preferences
     */
    public function updateCategorizationPreferences(array $preferences)
    {
        $current = $this->getCategorizationPreferences();
        $this->categorization_preferences = array_merge($current, $preferences);
        $this->save();
    }

    /**
     * Check if auto-categorization is enabled for this user
     */
    public function isAutoCategorizeEnabled()
    {
        return $this->auto_categorize_enabled;
    }

    /**
     * Relationships
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
}
