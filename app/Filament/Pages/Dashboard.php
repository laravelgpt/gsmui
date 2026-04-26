<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\PaymentService;
use App\Models\User;
use App\Models\Component;
use App\Models\Template;
use Illuminate\Support\Facades\DB;

class Dashboard extends Page
{
    protected static string $view = 'filament.pages.dashboard';

    public $mrr = 0;
    public $totalUsers = 0;
    public $activeUsers = 0;
    public $totalComponents = 0;
    public $totalTemplates = 0;
    public $totalPurchases = 0;
    public $recentPurchases = [];
    public $componentDownloads = [];

    public function mount(PaymentService $paymentService)
    {
        // Calculate MRR
        $this->mrr = $paymentService->getMRR();

        // User statistics
        $this->totalUsers = User::count();
        $this->activeUsers = User::where('subscription_status', 'active')->count();

        // Component and template statistics
        $this->totalComponents = Component::where('is_active', true)->count();
        $this->totalTemplates = Template::where('is_active', true)->count();

        // Purchase statistics
        $this->totalPurchases = DB::table('purchases')
            ->where('payment_status', 'completed')
            ->count();

        // Recent purchases
        $this->recentPurchases = DB::table('purchases')
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->where('purchases.payment_status', 'completed')
            ->select('purchases.*', 'users.name as user_name')
            ->orderBy('purchases.created_at', 'desc')
            ->take(10)
            ->get();

        // Component download stats (if tracked)
        $this->componentDownloads = Component::where('is_active', true)
            ->orderBy('download_count', 'desc')
            ->take(5)
            ->get(['name', 'download_count', 'category']);
    }

    protected function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\RecentPurchases::class,
            \App\Filament\Widgets\ComponentDownloads::class,
        ];
    }
}
