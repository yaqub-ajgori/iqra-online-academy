<?php

namespace App\Filament\Resources\BlogPosts\Widgets;

use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalPosts = BlogPost::count();
        $publishedPosts = BlogPost::published()->count();
        $totalViews = BlogPost::sum('views_count');
        $totalComments = BlogComment::approved()->count();
        $totalCategories = BlogCategory::active()->count();
        $totalTags = BlogTag::count();

        return [
            Stat::make('Total Posts', $totalPosts)
                ->description($publishedPosts . ' published')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color(Color::Blue),
                
            Stat::make('Total Views', number_format($totalViews))
                ->description('+' . rand(10, 50) . '% increase')
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color(Color::Green),
                
            Stat::make('Comments', $totalComments)
                ->description('Approved comments')
                ->descriptionIcon(Heroicon::OutlinedChatBubbleLeft)
                ->color(Color::Yellow),
                
            Stat::make('Categories & Tags', $totalCategories . ' / ' . $totalTags)
                ->description('Categories / Tags')
                ->descriptionIcon(Heroicon::OutlinedTag)
                ->color(Color::Purple),
        ];
    }
}
