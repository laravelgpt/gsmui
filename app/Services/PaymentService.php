<?php

namespace App\Services;

use App\Services\PaymentDataSanitizer;

use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process a one-time purchase
     */
    public function purchaseItem(User $user, string $purchasableType, int $purchasableId, float $amount, ?string $paymentMethodId = null): array
    {
        try {
            if (!in_array($purchasableType, ['component', 'template'])) {
                return ['success' => false, 'error' => 'Invalid purchasable type'];
            }

            $modelClass = $purchasableType === 'component' 
                ? 'App\\Models\\Component' 
                : 'App\\Models\\Template';

            // Check if already purchased
            $existing = Purchase::where('user_id', $user->id)
                ->where('purchasable_type', $modelClass)
                ->where('purchasable_id', $purchasableId)
                ->where('payment_status', 'completed')
                ->first();

            if ($existing) {
                return ['success' => true, 'already_owned' => true, 'message' => 'Already owned'];
            }

            $transactionId = null;
            $paymentStatus = 'pending';

            if ($paymentMethodId && $user->hasStripeId()) {
                try {
                    $charge = $user->charge((int)($amount * 100), $paymentMethodId, [
                        'description' => "Purchase: {$purchasableType} #{$purchasableId}",
                        'currency' => 'usd',
                    ]);
                    $transactionId = $charge->id;
                    $paymentStatus = $charge->status === 'succeeded' ? 'completed' : 'failed';
                } catch (\Exception $e) {
                    // Sensitive payment data redacted
        Log::channel('transactions')->error('Stripe charge error', ['message' => 'Charge failed']);
                    $paymentStatus = 'failed';
                }
            } else {
                // Simulated for development
                $transactionId = 'sim_' . uniqid();
                $paymentStatus = 'completed';
            }

            $purchase = Purchase::create([
                'user_id' => $user->id,
                'purchasable_type' => $modelClass,
                'purchasable_id' => $purchasableId,
                'amount' => $amount,
                'currency' => 'USD',
                'payment_status' => $paymentStatus,
                'transaction_id' => $transactionId,
                'payment_method' => $paymentMethodId ?? 'simulated',
            ]);

            return $paymentStatus === 'completed'
                ? ['success' => true, 'purchase' => $purchase]
                : ['success' => false, 'error' => 'Payment failed'];

        } catch (\Exception $e) {
            // Sensitive payment data redacted
        Log::channel('transactions')->error('Purchase error', ['message' => 'Purchase failed']);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Subscribe user to Pro
     */
    public function subscribe(User $user, string $priceId, string $paymentMethodId): array
    {
        try {
            $subscription = $user->newSubscription('pro', $priceId)->create($paymentMethodId);
            
            $user->subscription_status = 'active';
            $user->save();

            return ['success' => true, 'subscription' => $subscription];
        } catch (\Exception $e) {
            // Sensitive payment data redacted
        Log::channel('transactions')->error('Subscription error', ['message' => 'Subscription failed']);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(User $user): array
    {
        try {
            $user->subscription('pro')->cancel();
            $user->subscription_status = 'cancelled';
            $user->save();
            return ['success' => true];
        } catch (\Exception $e) {
            // Sensitive payment data redacted
        Log::channel('transactions')->error('Cancel error', ['message' => 'Cancellation failed']);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get billing history
     */
    public function getBillingHistory(User $user): array
    {
        $purchases = Purchase::where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $subscriptions = $user->subscriptions()->with('items')->get();

        return compact('purchases', 'subscriptions');
    }

    /**
     * Get MRR
     */
    public function getMRR(): float
    {
        // In production, calculate from active Stripe subscriptions
        $subscribedUsers = User::where('subscription_status', 'active')->count();
        return $subscribedUsers * 29.99; // Assuming $29.99/mo Pro plan
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue(): float
    {
        return (float) Purchase::where('payment_status', 'completed')->sum('amount');
    }
}
