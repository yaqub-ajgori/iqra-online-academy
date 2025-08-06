<?php

namespace App\Console\Commands;

use Database\Seeders\ComprehensiveTestSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:test-data {--fresh : Run fresh migration before seeding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup comprehensive test data for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Setting up comprehensive test data...');
        $this->newLine();

        // Fresh migration if requested
        if ($this->option('fresh')) {
            $this->warn('⚠️  Running fresh migration (this will delete all existing data)');
            if ($this->confirm('Are you sure you want to continue?')) {
                Artisan::call('migrate:fresh');
                $this->info('✅ Fresh migration completed');
            } else {
                $this->error('❌ Operation cancelled');
                return;
            }
        }

        // Run the comprehensive test seeder
        Artisan::call('db:seed', ['--class' => ComprehensiveTestSeeder::class]);
        
        $this->newLine();
        $this->info('🎯 Test data setup completed!');
        $this->newLine();
        
        // Display test accounts
        $this->table(
            ['Account Type', 'Email', 'Password', 'Role'],
            [
                ['Admin', 'admin@test.com', 'password', 'admin'],
                ['Teacher', 'teacher@test.com', 'password', 'teacher'],
                ['Student (Test)', 'student@test.com', 'password', 'student'],
            ]
        );

        $this->newLine();
        $this->info('📚 Student Test Scenarios Created:');
        $this->line('✅ Free Course - Completed with Certificate Available');
        $this->line('✅ Paid Course - In Progress (75% completed)'); 
        $this->line('✅ Mixed Content Course - Just Started (25% completed)');
        $this->line('❓ Quiz Course - Failed Quiz Attempt');
        $this->line('🎥 Video Course - Completed but Certificate Pending');
        $this->line('⏳ Pending Payment Course - Payment Verification Required');
        $this->line('❌ Cancelled Course - Enrollment Cancelled');
        
        $this->newLine();
        $this->info('🌐 You can now test:');
        $this->line('• Student Dashboard with all enrollment states');
        $this->line('• Learning Page with different content types'); 
        $this->line('• Certificate generation and download');
        $this->line('• Payment workflows and status');
        $this->line('• Course progress tracking');
        $this->line('• Quiz attempts and results');
        $this->line('• Mixed content lessons (nullable fields)');
        
        $this->newLine();
        $this->info('🔗 Access the application:');
        $this->line('Frontend: http://127.0.0.1:8002');
        $this->line('Admin Panel: http://127.0.0.1:8002/admin');
    }
}