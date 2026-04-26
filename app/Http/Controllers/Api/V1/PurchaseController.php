
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\Template;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchasable_type' => 'required|in:component,template',
            'purchasable_id' => 'required|integer',
            'payment_method_id' => 'nullable|string'
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Authentication required'], 401);
        }

        $purchasableType = $request->purchasable_type;
        $purchasableId = $request->purchasable_id;

        if ($purchasableType === 'component') {
            $item = Component::where('id', $purchasableId)->where('is_active', true)->first();
        } else {
            $item = Template::where('id', $purchasableId)->where('is_active', true)->first();
        }

        if (!$item) {
            return response()->json(['success' => false, 'error' => 'Item not found'], 404);
        }

        $amount = $item->metadata['price'] ?? ($purchasableType === 'component' ? 49.99 : 99.99);

        $result = $this->paymentService->purchaseItem(
            $user, $purchasableType, $purchasableId, $amount, $request->payment_method_id
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'purchase' => $result['purchase'],
                'message' => 'Purchase completed successfully'
            ]);
        }

        return response()->json(['success' => false, 'error' => $result['error']], 400);
    }

    public function index(Request $request)
    {
        $purchases = $request->user()->purchases()
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $purchases]);
    }

    public function billingHistory(Request $request)
    {
        $billingData = $this->paymentService->getBillingHistory($request->user());
        return response()->json(['success' => true, 'data' => $billingData]);
    }
}
