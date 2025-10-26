<?php

use App\Http\Controllers\Api\CategorizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

// Categorization routes
Route::prefix('categorization')->middleware('auth:sanctum')->group(function () {
    // Auto-categorize transactions
    Route::post('/transaction/{transaction}', [CategorizationController::class, 'categorizeTransaction'])
        ->name('categorization.transaction');
    Route::post('/transactions/batch', [CategorizationController::class, 'categorizeAllTransactions'])
        ->name('categorization.batch');

    // Categorization rules management
    Route::get('/rules', [CategorizationController::class, 'getRules'])
        ->name('categorization.rules.index');
    Route::post('/rules', [CategorizationController::class, 'createRule'])
        ->name('categorization.rules.store');
    Route::put('/rules/{rule}', [CategorizationController::class, 'updateRule'])
        ->name('categorization.rules.update');
    Route::delete('/rules/{rule}', [CategorizationController::class, 'deleteRule'])
        ->name('categorization.rules.destroy');

    // Utility routes
    Route::post('/rules/default', [CategorizationController::class, 'createDefaultRules'])
        ->name('categorization.rules.default');
    Route::post('/rules/test', [CategorizationController::class, 'testRule'])
        ->name('categorization.rules.test');
});
