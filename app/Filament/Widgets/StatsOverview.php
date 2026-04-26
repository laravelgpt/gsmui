<?php

namespace App\Filament\Widgets;

use App\Models\Component;
use App\Models\Template;
use App\Models\User;
use App\Services\PaymentService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Revenue', '$' . number_format($this->paymentService->getMRR(), 2))
                ->description('Monthly Recurring Revenue')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success')
                ->chart([700, 850, 920, 1100, 1200, 1350])
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Total Users', number_format(User::count()))
                ->description('Active members')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->chart([100, 150, 200, 250, 300, 350])
                ->icon('heroicon-o-user-group'),

            Stat::make('Components', Component::where('is_active', true)->count())
                ->description('Available components')
                ->descriptionIcon('heroicon-o-cube')
                ->color('info')
                ->chart([20, 25, 30, 35, 40, 45])
                ->icon('heroicon-o-puzzle-piece'),

            Stat::make('Templates', Template::where('is_active', true)->count())
                ->description('Available templates')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning')
                ->chart([5, 8, 10, 12, 15, 18])
                ->icon('heroicon-o-document-duplicate'),

            Stat::make('Subscriptions', User::where('subscription_status', 'active')->count())
                ->description('Active subscribers')
                ->descriptionIcon('heroicon-o-sparkles')
                ->color('success')
                ->chart([10, 12, 15, 18, 22, 25])
                ->icon('heroicon-o-shield-check'),

            Stat::make('Total Sales', '$' . number_format(
                \App\Models\Purchase::where('payment_status', 'completed')->sum('amount'),
                2
            ))
                ->description('Lifetime revenue')
                ->descriptionIcon('heroicon-o-receipt-percent')
                ->color('primary')
                ->icon('heroicon-o-credit-card'),
        ];
    }
}
