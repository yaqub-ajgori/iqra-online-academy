<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\Cache;

class EnrollmentTrendsChart extends ChartWidget
{
    protected ?string $heading = 'Enrollment Trends';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        return Cache::remember('enrollment_trends_chart', 300, function () {
            $months = collect();
            $enrollments = collect();

            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $months->push($month->format('M Y'));
                
                $monthlyEnrollments = CourseEnrollment::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('payment_status', 'completed')
                    ->count();
                    
                $enrollments->push($monthlyEnrollments);
            }

            return [
                'datasets' => [
                    [
                        'label' => 'New Enrollments',
                        'data' => $enrollments->toArray(),
                        'fill' => true,
                        'borderColor' => 'rgb(34, 197, 94)',
                        'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                        'tension' => 0.4,
                        'pointBackgroundColor' => 'rgb(34, 197, 94)',
                        'pointBorderColor' => '#fff',
                        'pointBorderWidth' => 2,
                        'pointRadius' => 4,
                    ],
                ],
                'labels' => $months->toArray(),
            ];
        });
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Monthly Student Enrollment Trends',
                    'font' => [
                        'size' => 16,
                        'weight' => 'bold',
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}