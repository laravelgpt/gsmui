<?php

namespace App\Services;

use App\Models\Component;
use App\Models\Template;
use App\Models\Purchase;
use App\Models\Download;
use App\Models\Analytics as AnalyticsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        $cacheKey = 'dashboard_stats_' . date('Y-m-d-H');

        return Cache::remember($cacheKey, 3600, function () {
            $now = Carbon::now();
            $today = Carbon::today();
            $weekAgo = Carbon::now()->subWeek();
            $monthAgo = Carbon::now()->subMonth();

            return [
                'revenue' => [
                    'total' => Purchase::where('status', 'completed')->sum('amount'),
                    'today' => Purchase::where('status', 'completed')->whereDate('created_at', $today)->sum('amount'),
                    'week' => Purchase::where('status', 'completed')->whereDate('created_at', '>=', $weekAgo)->sum('amount'),
                    'month' => Purchase::where('status', 'completed')->whereDate('created_at', '>=', $monthAgo)->sum('amount'),
                ],
                'users' => [
                    'total' => \App\Models\User::count(),
                    'new_today' => \App\Models\User::whereDate('created_at', $today)->count(),
                    'new_week' => \App\Models\User::whereDate('created_at', '>=', $weekAgo)->count(),
                ],
                'components' => [
                    'total' => Component::count(),
                    'active' => Component::where('is_active', true)->count(),
                    'downloads' => Download::count(),
                    'new_today' => Component::whereDate('created_at', $today)->count(),
                ],
                'templates' => [
                    'total' => Template::count(),
                    'active' => Template::where('is_active', true)->count(),
                    'sold' => Purchase::whereHas('template')->where('status', 'completed')->count(),
                ],
                'purchases' => [
                    'total' => Purchase::where('status', 'completed')->count(),
                    'today' => Purchase::where('status', 'completed')->whereDate('created_at', $today)->count(),
                    'week' => Purchase::where('status', 'completed')->whereDate('created_at', '>=', $weekAgo)->count(),
                ],
            ];
        });
    }

    /**
     * Get revenue chart data
     */
    public function getRevenueChartData($period = '7d'): array
    {
        $cacheKey = "revenue_chart_{$period}_" . date('Y-m-d');

        return Cache::remember($cacheKey, 3600, function () use ($period) {
            $query = Purchase::where('status', 'completed')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(amount) as revenue'),
                    DB::raw('COUNT(*) as count')
                );

            switch ($period) {
                case '24h':
                    $query->where('created_at', '>=', Carbon::now()->subHours(24))
                          ->groupBy(DB::raw('HOUR(created_at)'))
                          ->orderBy('date');
                    break;

                case '7d':
                    $query->where('created_at', '>=', Carbon::now()->subDays(7))
                          ->groupBy(DB::raw('DATE(created_at)'))
                          ->orderBy('date');
                    break;

                case '30d':
                    $query->where('created_at', '>=', Carbon::now()->subDays(30))
                          ->groupBy(DB::raw('DATE(created_at)'))
                          ->orderBy('date');
                    break;

                case '90d':
                    $query->where('created_at', '>=', Carbon::now()->subDays(90))
                          ->groupBy(DB::raw('DATE(created_at)'))
                          ->orderBy('date');
                    break;

                case '1y':
                    $query->where('created_at', '>=', Carbon::now()->subYear())
                          ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'))
                          ->orderBy('date');
                    break;
            }

            return $query->get()->map(function ($item) use ($period) {
                return [
                    'date' => $item->date,
                    'revenue' => (float) $item->revenue,
                    'count' => (int) $item->count,
                    'label' => $this->formatDateLabel($item->date, $period),
                ];
            })->toArray();
        });
    }

    /**
     * Get top selling components
     */
    public function getTopSellingComponents($limit = 10): array
    {
        $cacheKey = "top_components_{$limit}_" . date('Y-m-d');

        return Cache::remember($cacheKey, 3600, function () use ($limit) {
            return Component::select(
                'components.*',
                DB::raw('COUNT(purchases.id) as sales_count'),
                DB::raw('COALESCE(SUM(purchases.amount), 0) as revenue')
            )
                ->leftJoin('purchases', function ($join) {
                    $join->on('purchases.component_id', '=', 'components.id')
                         ->where('purchases.status', 'completed');
                })
                ->groupBy('components.id')
                ->orderBy('sales_count', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($component) {
                    return [
                        'id' => $component->id,
                        'name' => $component->name,
                        'category' => $component->category,
                        'sales_count' => (int) $component->sales_count,
                        'revenue' => (float) $component->revenue,
                        'price' => (float) $component->price,
                    ];
                })->toArray();
        });
    }

    /**
     * Get top selling templates
     */
    public function getTopSellingTemplates($limit = 10): array
    {
        $cacheKey = "top_templates_{$limit}_" . date('Y-m-d');

        return Cache::remember($cacheKey, 3600, function () use ($limit) {
            return Template::select(
                'templates.*',
                DB::raw('COUNT(purchases.id) as sales_count'),
                DB::raw('COALESCE(SUM(purchases.amount), 0) as revenue')
            )
                ->leftJoin('purchases', function ($join) {
                    $join->on('purchases.template_id', '=', 'templates.id')
                         ->where('purchases.status', 'completed');
                })
                ->groupBy('templates.id')
                ->orderBy('sales_count', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($template) {
                    return [
                        'id' => $template->id,
                        'name' => $template->name,
                        'category' => $template->category,
                        'sales_count' => (int) $template->sales_count,
                        'revenue' => (float) $template->revenue,
                        'price' => (float) $template->price,
                    ];
                })->toArray();
        });
    }

    /**
     * Get user activity data
     */
    public function getUserActivity($userId, $period = '30d'): array
    {
        $cacheKey = "user_activity_{$userId}_{$period}_" . date('Y-m-d');

        return Cache::remember($cacheKey, 3600, function () use ($userId, $period) {
            $startDate = match ($period) {
                '7d' => Carbon::now()->subDays(7),
                '30d' => Carbon::now()->subDays(30),
                '90d' => Carbon::now()->subDays(90),
                default => Carbon::now()->subDays(30),
            };

            return [
                'purchases' => Purchase::where('user_id', $userId)
                    ->where('status', 'completed')
                    ->whereDate('created_at', '>=', $startDate)
                    ->count(),
                'total_spent' => Purchase::where('user_id', $userId)
                    ->where('status', 'completed')
                    ->whereDate('created_at', '>=', $startDate)
                    ->sum('amount'),
                'downloads' => Download::where('user_id', $userId)
                    ->whereDate('created_at', '>=', $startDate)
                    ->count(),
                'chat_messages' => \App\Models\ComponentChat::where('user_id', $userId)
                    ->whereDate('created_at', '>=', $startDate)
                    ->count(),
                'activity_timeline' => \App\Models\Analytics::where('user_id', $userId)
                    ->whereDate('created_at', '>=', $startDate)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date')
                    ->get()
                    ->toArray(),
            ];
        });
    }

    /**
     * Get popular categories
     */
    public function getPopularCategories(): array
    {
        $cacheKey = 'popular_categories_' . date('Y-m-d');

        return Cache::remember($cacheKey, 3600, function () {
            $components = Component::select(
                'category',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN type = "free" THEN 1 ELSE 0 END) as free_count'),
                DB::raw('SUM(CASE WHEN type = "paid" THEN 1 ELSE 0 END) as paid_count'),
                DB::raw('SUM(CASE WHEN type = "premium" THEN 1 ELSE 0 END) as premium_count')
            )
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
                ->toArray();

            $templates = Template::select(
                'category',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN type = "free" THEN 1 ELSE 0 END) as free_count'),
                DB::raw('SUM(CASE WHEN type = "paid" THEN 1 ELSE 0 END) as paid_count')
            )
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
                ->toArray();

            return compact('components', 'templates');
        });
    }

    /**
     * Track user interaction
     */
    public function trackInteraction($userId, $action, $metadata = [])
    {
        AnalyticsModel::create([
            'user_id' => $userId,
            'action' => $action,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Clear related cache
        Cache::tags(['analytics'])->flush();
    }

    /**
     * Format date label for charts
     */
    private function formatDateLabel($date, $period): string
    {
        $carbon = Carbon::parse($date);

        switch ($period) {
            case '24h':
                return $carbon->format('H:00');
            case '7d':
                return $carbon->format('D M d');
            case '30d':
            case '90d':
                return $carbon->format('M d');
            case '1y':
                return $carbon->format('M Y');
            default:
                return $carbon->format('Y-m-d');
        }
    }

    /**
     * Clear all analytics cache
     */
    public function clearCache(): void
    {
        Cache::tags(['analytics', 'dashboard'])->flush();
    }
}
ENDOFFILE
echo "AnalyticsService created"
