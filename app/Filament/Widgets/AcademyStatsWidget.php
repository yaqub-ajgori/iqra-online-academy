<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Student;
use App\Models\Course;
use App\Models\Payment;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\Cache;

class AcademyStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $user = auth()->user();
        $cacheKey = $user->isTeacher() ? 'teacher_dashboard_stats_' . $user->id : 'admin_dashboard_stats';
        
        // Cache stats for 5 minutes to improve performance
        return Cache::remember($cacheKey, 300, function () use ($user) {
            if ($user->isTeacher()) {
                return $this->getTeacherStats($user);
            } else {
                return $this->getAdminStats();
            }
        });
    }
    
    /**
     * Get stats for teachers - only their course-specific data
     */
    private function getTeacherStats($user): array
    {
        $teacher = $user->teacher;
        if (!$teacher) {
            return [];
        }
        
        // Get teacher's courses
        $teacherCourses = Course::where('instructor_id', $teacher->id)
            ->orWhereHas('instructors', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            });
            
        $myCourses = $teacherCourses->count();
        $publishedCourses = $teacherCourses->where('status', 'published')->count();
        
        // Get students enrolled in teacher's courses
        $myStudents = Student::whereHas('enrollments', function ($q) use ($teacher) {
            $q->whereHas('course', function ($courseQuery) use ($teacher) {
                $courseQuery->where('instructor_id', $teacher->id)
                           ->orWhereHas('instructors', function ($instQuery) use ($teacher) {
                               $instQuery->where('teacher_id', $teacher->id);
                           });
            });
        })->count();
        
        // Get active enrollments for teacher's courses
        $activeEnrollments = CourseEnrollment::where('is_active', true)
            ->where('payment_status', 'completed')
            ->whereHas('course', function ($q) use ($teacher) {
                $q->where('instructor_id', $teacher->id)
                  ->orWhereHas('instructors', function ($instQuery) use ($teacher) {
                      $instQuery->where('teacher_id', $teacher->id);
                  });
            })->count();
            
        // Get recent reviews for teacher's courses
        $recentReviews = \App\Models\CourseReview::whereHas('course', function ($q) use ($teacher) {
            $q->where('instructor_id', $teacher->id)
              ->orWhereHas('instructors', function ($instQuery) use ($teacher) {
                  $instQuery->where('teacher_id', $teacher->id);
              });
        })->where('created_at', '>=', now()->subDays(30))->count();

        return [
            Stat::make('My Courses', number_format($myCourses))
                ->icon('heroicon-m-book-open')
                ->color('primary')
                ->description("{$publishedCourses} published")
                ->descriptionIcon('heroicon-m-eye'),
                
            Stat::make('My Students', number_format($myStudents))
                ->icon('heroicon-m-academic-cap')
                ->color('success')
                ->description('Enrolled in my courses')
                ->descriptionIcon('heroicon-m-user-group'),
                
            Stat::make('Active Enrollments', number_format($activeEnrollments))
                ->icon('heroicon-m-clipboard-document-check')
                ->color('success')
                ->description('Paid & active students')
                ->descriptionIcon('heroicon-m-check-circle'),
                
            Stat::make('Recent Reviews', number_format($recentReviews))
                ->icon('heroicon-m-star')
                ->color('warning')
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis'),
        ];
    }
    
    /**
     * Get stats for admins - system-wide data
     */
    private function getAdminStats(): array
    {
        // Get only essential metrics with optimized queries
        $totalStudents = Student::count();
        $newStudentsThisMonth = Student::whereMonth('created_at', now()->month)->count();
        
        $activeEnrollments = CourseEnrollment::where('is_active', true)
            ->where('payment_status', 'completed')->count();
        
        $publishedCourses = Course::where('status', 'published')->count();
        
        $thisMonthRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)->sum('amount');

        return [
            Stat::make('Total Students', number_format($totalStudents))
                ->icon('heroicon-m-academic-cap')
                ->color('primary')
                ->description("{$newStudentsThisMonth} joined this month")
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
                
            Stat::make('Active Enrollments', number_format($activeEnrollments))
                ->icon('heroicon-m-clipboard-document-check')
                ->color('success')
                ->description('Paid & active students')
                ->descriptionIcon('heroicon-m-check-circle'),
                
            Stat::make('Published Courses', number_format($publishedCourses))
                ->icon('heroicon-m-book-open')
                ->color('primary')
                ->description('Available for enrollment')
                ->descriptionIcon('heroicon-m-eye'),
                
            Stat::make('Monthly Revenue', '৳' . number_format($thisMonthRevenue, 0))
                ->icon('heroicon-m-banknotes')
                ->color($thisMonthRevenue > 0 ? 'success' : 'gray')
                ->description('Current month earnings')
                ->descriptionIcon($thisMonthRevenue > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-minus'),
        ];
    }
} 