<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // User who performed the action
            $table->string('table_name'); // Which table was affected
            $table->unsignedBigInteger('record_id')->nullable(); // Which record was affected
            $table->string('action'); // CREATE, READ, UPDATE, DELETE, UNAUTHORIZED_ACCESS
            $table->string('description'); // Human-readable description
            $table->json('details')->nullable(); // Additional details about the action
            $table->string('ip_address')->nullable(); // User's IP address
            $table->text('user_agent')->nullable(); // User's browser/agent
            $table->timestamp('created_at'); // When the action occurred
            
            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['table_name', 'action']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};