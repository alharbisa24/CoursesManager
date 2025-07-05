<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CoursesChart extends ChartWidget
{
    protected static ?string $heading = 'Orders Per Course';
    public ?string $filter = 'today';


    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }
    protected function getData(): array
    {
        $range = match ($this->filter) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };

        $data = DB::table('orders')
            ->select('courses.title', DB::raw('COUNT(orders.id) as total_orders'))
            ->join('courses', 'orders.course_id', '=', 'courses.id')
            ->whereBetween('orders.created_at', $range)
            ->groupBy('courses.title')
            ->orderByDesc('total_orders')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->pluck('total_orders'),
                    'backgroundColor' => '#3B82F6',
                ],
            ],
            'labels' => $data->pluck('title'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
