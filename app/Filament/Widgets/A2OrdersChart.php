<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class A2OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders Chart';
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

        $orders = Order::whereBetween('created_at', $range)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();


        return [
            'datasets' => [
                [
                    'label' => 'Total Orders',
                    'data' => $orders->map(fn ($item) => [
                        'x' => Carbon::parse($item->date)->toISOString(),
                        'y' => $item->count,
                    ]),
                    'fill' => true,
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'tension' => 0.3,
                ],
            ],
        ];
    }
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'type' => 'time',
                    'time' => [
                        'unit' => 'day',
                        'tooltipFormat' => 'MMM d',
                        'displayFormats' => [
                            'day' => 'MMM d',
                        ],
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Date',
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Total Orders',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
