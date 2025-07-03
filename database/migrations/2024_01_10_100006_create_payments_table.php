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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            // Payment Details
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('BDT');
            $table->enum('payment_method', ['bkash', 'nagad', 'rocket', 'bank_transfer', 'card']);
            
            //  transaction id 
            $table->string('transaction_id')->unique()->nullable(); // Gateway transaction ID
            
            // Payment Status
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');

            
            // Mobile Banking Details (for Bangladesh)
            $table->string('sender_number', 20)->nullable(); // Customer mobile number
            $table->string('receiver_number', 20)->nullable(); // Merchant mobile number
            
            // Bank Transfer Details
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('branch_name')->nullable();
            
            // Refund Information
            
            $table->timestamps();

            $table->index('status', 'idx_payments_status');
            $table->index('payment_method', 'idx_payments_method');
            $table->index('student_id', 'idx_payments_student');
            $table->index('course_id', 'idx_payments_course');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}; 