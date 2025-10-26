<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->string('merchant')->nullable();
            $table->boolean('auto_categorized')->default(false);
            $table->datetime('transaction_date');
            $table->string('external_id')->nullable();
            $table->enum('type', ['debit', 'credit'])->default('debit');
            $table->enum('status', ['pending', 'posted', 'cancelled'])->default('posted');
            $table->timestamps();

            $table->index(['user_id', 'transaction_date']);
            $table->index(['account_id', 'transaction_date']);
            $table->index(['category_id']);
            $table->index(['auto_categorized']);
            $table->unique(['external_id', 'account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
