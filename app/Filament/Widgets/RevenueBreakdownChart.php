<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Payment;
use App\Models\Donation;
use Illuminate\Support\Facades\Cache;

class RevenueBreakdownChart extends ChartWidget
{
    protected ?string $heading = 'Revenue Breakdown';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'md';
    protected ?string $maxHeight = '350px';

    protected function getData(): array
    {
        return Cache::remember('revenue_breakdown_chart', 300, function () {
            $courseRevenue = Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');

            $donations = Donation::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');

            $totalCourseRevenue = Payment::where('status', 'completed')->sum('amount');
            $totalDonations = Donation::sum('amount');

            $data = [];
            $labels = [];
            $colors = [];

            if ($courseRevenue > 0) {
                $data[] = $courseRevenue;
                $labels[] = 'Course Revenue (৳' . number_format($courseRevenue, 0) . ')';
                $colors[] = 'rgba(59, 130, 246, 0.8)';
            }

            if ($donations > 0) {
                $data[] = $donations;
                $labels[] = 'Donations (৳' . number_format($donations, 0) . ')';
                $colors[] = 'rgba(34, 197, 94, 0.8)';
            }

            if (empty($data)) {
                if ($totalCourseRevenue > 0) {
                    $data[] = $totalCourseRevenue;
                    $labels[] = 'Total Course Revenue (৳' . number_format($totalCourseRevenue, 0) . ')';
                    $colors[] = 'rgba(59, 130, 246, 0.8)';
                }

                if ($totalDonations > 0) {
                    $data[] = $totalDonations;
                    $labels[] = 'Total Donations (৳' . number_format($totalDonations, 0) . ')';
                    $colors[] = 'rgba(34, 197, 94, 0.8)';
                }
            }

            if (empty($data)) {
                $data = [1];
                $labels = ['No Revenue Data'];
                $colors = ['rgba(156, 163, 175, 0.8)'];
            }

            return [
                'datasets' => [
                    [
                        'data' => $data,
                        'backgroundColor' => $colors,
                        'borderColor' => array_map(fn($color) => str_replace('0.8', '1', $color), $colors),
                        'borderWidth' => 2,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
                'title' => [
                    'display' => true,
                    'text' => 'This Month\'s Revenue Sources',
                    'font' => [
                        'size' => 16,
                        'weight' => 'bold',
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            const label = context.label || "";
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ": " + percentage + "%";
                        }'
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'cutout' => '50%',
            'elements' => [
                'arc' => [
                    'borderWidth' => 2,
                ],
            ],
        ];
    }
}