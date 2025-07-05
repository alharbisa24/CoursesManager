<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Course_list;
use App\Models\Course_list_videos;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class A1StatsOverview extends BaseWidget
{

    protected function getStats(): array
    {
        $coursesPerDay = Course::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chartData[] = $coursesPerDay[$date] ?? 0;
        }


        $VideosPerDay = Course_list_videos::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $VideoschartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $VideoschartData[] = $VideosPerDay[$date] ?? 0;
        }


        $courses = Course::withCount('orders')->get();
        $fullCoursesCount = $courses->filter(fn ($course) => $course->orders_count >= $course->seats)->count();



        $OrdersPerDay = Order::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $OrderschartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $OrderschartData[] = $OrdersPerDay[$date] ?? 0;
        }

        return [
            Stat::make('Total Profits', Order::all()->sum('price'))->description('Sum of profits')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->chart($chartData),
            Stat::make('Total courses', Course::all()->count())->description('Total number of courses')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->chart($chartData),
            Stat::make('Total full courses',$fullCoursesCount)->description('Total number of courses that reach full seats')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('Total Orders', Order::all()->count())->description('Total number of orders')
                ->descriptionIcon('heroicon-m-shopping-cart', IconPosition::Before)
                ->chart($OrderschartData),


            Stat::make('Total Completed', Order::where('status','completed')->count())->description('Total number of completed orders')
                ->descriptionIcon('heroicon-m-shopping-cart', IconPosition::Before)
                ->chart($OrderschartData),

            Stat::make('Total Customers', Customer::count())->description('Total number of customers')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before),

            Stat::make('Total users', User::count())->description('Total number of users')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before),

            Stat::make('Total Course lists', Course_list::all()->count())->description('Total number of lists')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('total Videos', Course_list_videos::all()->count())->description('Total number of videos')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->chart($VideoschartData),
        ];
    }
}
