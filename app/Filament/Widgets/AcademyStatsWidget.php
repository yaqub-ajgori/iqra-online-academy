<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Student;
use App\Models\Course;
use App\Models\Payment;
use App\Models\CourseEnrollment;
use App\Models\User;
use App\Models\Donation;

class AcademyStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Iqra Online Academy Statistics';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', Student::count())
                ->icon('heroicon-m-academic-cap')
                ->color('info')
                ->description('Active Students')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([12, 17, 14, 20, 22, 25, 30]),
            Stat::make('Total Courses', Course::count())
                ->icon('heroicon-m-book-open')
                ->color('primary')
                ->description('Available Courses')
                ->descriptionIcon('heroicon-m-bookmark')
                ->chart([5, 7, 8, 10, 12, 13, 15]),
            Stat::make('Total Payments', Payment::count())
                ->icon('heroicon-m-credit-card')
                ->color('success')
                ->description('Payments Received')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([2, 4, 6, 8, 10, 12, 14]),
            Stat::make('Total Earning', '৳' . number_format(\App\Models\Payment::where('status', 'completed')->sum('amount'), 2))
                ->icon('heroicon-m-banknotes')
                ->color('success')
                ->description('Completed Payments')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([1000, 1200, 1500, 1700, 2000, 2500, 3000]),
            Stat::make('Total Donations', Donation::count())
                ->icon('heroicon-m-heart')
                ->color('rose')
                ->description('Donations Received')
                ->descriptionIcon('heroicon-m-heart')
                ->chart([1, 2, 3, 4, 5, 6, 7]),
            Stat::make('Donation Amount', '৳' . number_format(Donation::sum('amount'), 2))
                ->icon('heroicon-m-heart')
                ->color('rose')
                ->description('Total Donated')
                ->descriptionIcon('heroicon-m-heart')
                ->chart([100, 200, 300, 400, 500, 600, 700]),
            Stat::make('Total Enrollments', CourseEnrollment::count())
                ->icon('heroicon-m-clipboard-document-list')
                ->color('warning')
                ->description('Course Enrollments')
                ->descriptionIcon('heroicon-m-arrow-right-circle')
                ->chart([8, 10, 12, 14, 16, 18, 20]),
            Stat::make('Enrollment Pending', \App\Models\CourseEnrollment::where('payment_status', 'pending')->count())
                ->icon('heroicon-m-clock')
                ->color('warning')
                ->description('Pending Payment')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->chart([2, 3, 4, 5, 4, 3, 2]),
            Stat::make('Total Users', User::count())
                ->icon('heroicon-m-users')
                ->color('secondary')
                ->description('System Users')
                ->descriptionIcon('heroicon-m-user')
                ->chart([3, 5, 7, 9, 11, 13, 15]),
            Stat::make('Payment Pending', \App\Models\Payment::where('status', 'pending')->count())
                ->icon('heroicon-m-credit-card')
                ->color('warning')
                ->description('Awaiting Completion')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->chart([1, 2, 2, 3, 2, 1, 1]),
        ];
    }
} 