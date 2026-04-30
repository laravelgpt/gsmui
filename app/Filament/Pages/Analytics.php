<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\PaymentService;
use App\Models\Purchase;
use App\Models\User;

class Analytics extends Page
{
    protected static string $view = 'filament.pages.analytics';

    public $mrr;
    public $totalRevenue;
    public $totalPurchases;
    public $weeklyRevenue;

    public function mount(PaymentService $paymentService)
    {
        $this->mrr = $paymentService->getMRR();
        $this->totalRevenue = $paymentService->getTotalRevenue();
        $this->totalPurchases = Purchase::where('payment_status', 'completed')->count();
        
        $this->weeklyRevenue = Purchase::where('payment_status', 'completed')
            ->whereBetween('created_at', [now()->subWeek(), now()])
            ->sum('amount');
    }
}
