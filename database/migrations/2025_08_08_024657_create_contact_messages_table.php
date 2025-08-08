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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['unread', 'read', 'replied', 'resolved'])->default('unread');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->json('metadata')->nullable(); // For storing additional info like IP, user agent, etc.
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['status', 'created_at']);
            $table->index(['priority', 'created_at']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
