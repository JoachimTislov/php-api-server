<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
            $table->json('categorization_preferences')->nullable();  // For auto-categorization settings
            $table->boolean('auto_categorize_enabled')->default(true);
            $table->timestamps();
            $table->softDeletes();  // Add soft deletes for data integrity

            // Add indexes for performance
            $table->index('email');
            $table->index('created_at');
            $table->index('email_verified_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
