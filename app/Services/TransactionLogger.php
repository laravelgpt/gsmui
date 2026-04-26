<?php

namespace App\Services;

use App\Models\Purchase;
use Illuminate\Support\Facades\Log;

/**
 * Transaction Logger Service
 */
class TransactionLogger
{
    /**
     * Log a purchase transaction
     */
    public function logPurchase(Purchase $purchase, $metadata = [])
    {
        Log::channel('transactions')->info('Purchase completed', [
            'purchase_id' => $purchase->id,
            'user_id' => $purchase->user_id,
            'amount' => $purchase->amount,
            'currency' => $purchase->currency,
            'gateway' => $purchase->payment_method,
            'status' => $purchase->payment_status,
            'metadata' => $metadata,
        ]);
    }
    
    /**
     * Log a refund
     */
    public function logRefund($purchaseId, $amount, $reason = null)
    {
        Log::channel('transactions')->info('Refund processed', [
            'purchase_id' => $purchaseId,
            'amount' => $amount,
            'reason' => $reason,
        ]);
    }
}