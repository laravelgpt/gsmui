
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\Purchase;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function revenue(Request $request)
    {
        $timeframe = $request->query('timeframe', 'month');
        $query = Purchase::where('payment_status', 'completed');

        switch ($timeframe) {
            case 'week':
                $query->whereBetween('created_at', [now()->subWeek(), now()]);
                $groupBy = DB::raw('DATE(created_at)');
                break;
            case 'month':
                $query->whereBetween('created_at', [now()->subMonth(), now()]);
                $groupBy = DB::raw('DATE(created_at)');
                break;
            case 'year':
                $query->whereBetween('created_at', [now()->subYear(), now()]);
                $groupBy = DB::raw('MONTH(created_at)');
                break;
            default:
                $groupBy = DB::raw('DATE(created_at)');
        }

        $revenueByDate = $query->select(
            DB::raw($groupBy . ' as period'),
            DB::raw('SUM(amount) as total')
        )->groupBy('period')->orderBy('period')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'revenue_by_period' => $revenueByDate,
                'total_revenue' => $query->sum('amount'),
                'average_order_value' => $query->avg('amount'),
                'total_orders' => $query->count(),
            ]
        ]);
    }

    public function downloads(Request $request)
    {
        $components = Component::where('is_active', true)
            ->orderBy('download_count', 'desc')
            ->take(10)
            ->get();

        $purchases = Purchase::where('payment_status', 'completed')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'top_components' => $components,
                'purchase_trends' => $purchases,
                'total_downloads' => Component::sum('download_count'),
                'total_purchases' => $purchases->count(),
            ]
        ]);
    }

    public function users(Request $request)
    {
        $newUsersByDate = User::whereBetween('created_at', [now()->subMonth(), now()])
            ->select(DB::raw('DATE(created_at) as period'), DB::raw('COUNT(*) as count'))
            ->groupBy('period')->orderBy('period')->get();

        $usersByStatus = User::select('subscription_status', DB::raw('COUNT(*) as count'))
            ->groupBy('subscription_status')->get();

        $roleDistribution = User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'new_users_by_period' => $newUsersByDate,
                'users_by_status' => $usersByStatus,
                'role_distribution' => $roleDistribution,
                'total_users' => User::count(),
                'active_subscribers' => User::where('subscription_status', 'active')->count(),
            ]
        ]);
    }

    public function dashboard(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'mrr' => $this->paymentService->getMRR(),
                'total_revenue' => $this->paymentService->getTotalRevenue(),
                'total_users' => User::count(),
                'active_users' => User::where('subscription_status', 'active')->count(),
                'total_components' => Component::active()->count(),
                'total_templates' => Template::active()->count(),
                'total_purchases' => Purchase::where('payment_status', 'completed')->count(),
                'recent_purchases' => Purchase::with('user')->where('payment_status', 'completed')->latest()->take(10)->get(),
            ]
        ]);
    }
}
