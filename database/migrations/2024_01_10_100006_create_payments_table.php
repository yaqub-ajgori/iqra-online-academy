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
            
            // Payment Gateway Data
            $table->string('transaction_id')->unique()->nullable(); // Gateway transaction ID
            $table->string('gateway_payment_id')->nullable(); // Gateway specific ID
            $table->json('gateway_response')->nullable(); // Full gateway response
            
            // Payment Status
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->text('failure_reason')->nullable();
            
            // Mobile Banking Details (for Bangladesh)
            $table->string('sender_number', 20)->nullable(); // Customer mobile number
            $table->string('receiver_number', 20)->nullable(); // Merchant mobile number
            $table->string('reference_number')->nullable(); // Payment reference
            
            // Bank Transfer Details
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('branch_name')->nullable();
            
            // Refund Information
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->timestamp('refund_date')->nullable();
            $table->text('refund_reason')->nullable();
            $table->foreignId('refunded_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Metadata
            $table->text('payment_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('student_id', 'idx_payments_student');
            $table->index('course_id', 'idx_payments_course');
            $table->index('status', 'idx_payments_status');
            $table->index('payment_method', 'idx_payments_method');
            $table->index('transaction_id', 'idx_payments_transaction');
            $table->index('payment_date', 'idx_payments_date');
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