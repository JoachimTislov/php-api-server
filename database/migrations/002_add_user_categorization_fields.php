<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add categorization preferences for auto-categorization
            $table->json('categorization_preferences')->nullable();
            $table->boolean('auto_categorize_enabled')->default(true);

            // Add soft deletes for data integrity
            $table->softDeletes();

            // Add performance indexes
            $table->index('email');
            $table->index('created_at');
            $table->index('email_verified_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['categorization_preferences', 'auto_categorize_enabled']);
            $table->dropSoftDeletes();
            $table->dropIndex(['email']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['email_verified_at']);
        });
    }
};
