
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Component;
use App\Models\Template;
use App\Services\PaymentService;

class StripeWebhookController extends Controller
{
    protected $paymentService;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }

    /**
     * Handle Stripe webhook events
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            // Verify webhook signature in production
            if (config('app.env') === 'production') {
                $endpointSecret = config('services.stripe.webhook_secret');
                if (empty($endpointSecret)) {
                    Log::error('Stripe webhook secret not configured');
                    return response()->json(['error' => 'Webhook secret not configured'], 500);
                }
                
                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $sigHeader,
                    $endpointSecret
                );
            } else {
                $data = json_decode($payload, true);
                $event = \Stripe\Event::constructFrom($data);
            }

            $this->processEvent($event);

            return response()->json(['received' => true]);

        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid Stripe webhook signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Process Stripe event
     */
    protected function processEvent($event)
    {
        $type = $event->type;
        $data = $event->data->object;

        Log::info('Processing Stripe event: ' . $type, ['event_id' => $event->id]);

        switch ($type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($data);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($data);
                break;

            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($data);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($data);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionCancelled($data);
                break;

            case 'invoice.payment_succeeded':
                $this->handleInvoicePaid($data);
                break;

            case 'invoice.payment_failed':
                $this->handleInvoiceFailed($data);
                break;

            case 'charge.refunded':
                $this->handleChargeRefunded($data);
                break;

            default:
                Log::debug('Unhandled Stripe event type: ' . $type);
                break;
        }
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSucceeded($paymentIntent)
    {
        $user = $this->getUserFromPaymentIntent($paymentIntent);
        if (!$user) {
            Log::warning('No user found for payment intent: ' . $paymentIntent->id);
            return;
        }

        // Update purchase status
        if (isset($paymentIntent->metadata->purchase_id)) {
            $purchase = Purchase::find($paymentIntent->metadata->purchase_id);
            if ($purchase && $purchase->payment_status !== 'completed') {
                $purchase->update([
                    'payment_status' => 'completed',
                    'transaction_id' => $paymentIntent->id,
                ]);
                Log::info('Purchase marked as completed: ' . $purchase->id);
            }
        }

        // Send notification
        $this->sendPaymentSuccessNotification($user, $paymentIntent);
    }

    /**
     * Handle failed payment
     */
    protected function handlePaymentFailed($paymentIntent)
    {
        $user = $this->getUserFromPaymentIntent($paymentIntent);
        if (!$user) {
            return;
        }

        // Update purchase status
        if (isset($paymentIntent->metadata->purchase_id)) {
            $purchase = Purchase::find($paymentIntent->metadata->purchase_id);
            if ($purchase && $purchase->payment_status !== 'failed') {
                $purchase->update([
                    'payment_status' => 'failed',
                    'transaction_id' => $paymentIntent->id,
                ]);
            }
        }

        // Send notification
        $this->sendPaymentFailedNotification($user, $paymentIntent);

        Log::warning('Payment failed for user: ' . $user->id, [
            'payment_intent' => $paymentIntent->id,
            'amount' => $paymentIntent->amount,
        ]);
    }

    /**
     * Handle subscription created
     */
    protected function handleSubscriptionCreated($subscription)
    {
        $user = $this->getUserFromSubscription($subscription);
        if (!$user) {
            return;
        }

        $user->update([
            'subscription_status' => 'active',
            'stripe_id' => $subscription->customer,
        ]);

        // Create purchase record
        Purchase::create([
            'user_id' => $user->id,
            'purchasable_type' => '',
            'purchasable_id' => 0,
            'amount' => $subscription->items->data[0]->price->unit_amount / 100,
            'currency' => strtoupper($subscription->currency),
            'payment_status' => 'completed',
            'transaction_id' => $subscription->id,
            'payment_method' => 'stripe',
        ]);

        Log::info('Subscription created for user: ' . $user->id);
        $this->sendSubscriptionCreatedNotification($user, $subscription);
    }

    /**
     * Handle subscription updated
     */
    protected function handleSubscriptionUpdated($subscription)
    {
        $user = $this->getUserFromSubscription($subscription);
        if (!$user) {
            return;
        }

        // Check if subscription is active or past due
        if ($subscription->status === 'active') {
            $user->update(['subscription_status' => 'active']);
        } elseif ($subscription->status === 'past_due') {
            $user->update(['subscription_status' => 'past_due']);
            $this->sendSubscriptionPastDueNotification($user, $subscription);
        } elseif ($subscription->status === 'canceled') {
            $user->update(['subscription_status' => 'cancelled']);
        }

        Log::info('Subscription updated for user: ' . $user->id, [
            'status' => $subscription->status,
        ]);
    }

    /**
     * Handle subscription cancelled
     */
    protected function handleSubscriptionCancelled($subscription)
    {
        $user = $this->getUserFromSubscription($subscription);
        if (!$user) {
            return;
        }

        $user->update(['subscription_status' => 'cancelled']);

        Log::info('Subscription cancelled for user: ' . $user->id);
        $this->sendSubscriptionCancelledNotification($user, $subscription);
    }

    /**
     * Handle invoice paid
     */
    protected function handleInvoicePaid($invoice)
    {
        $user = $this->getUserFromInvoice($invoice);
        if (!$user) {
            return;
        }

        // Ensure subscription is active
        $user->update(['subscription_status' => 'active']);

        Log::info('Invoice paid for user: ' . $user->id, [
            'invoice_id' => $invoice->id,
            'amount' => $invoice->amount_paid,
        ]);
    }

    /**
     * Handle invoice failed
     */
    protected function handleInvoiceFailed($invoice)
    {
        $user = $this->getUserFromInvoice($invoice);
        if (!$user) {
            return;
        }

        $user->update(['subscription_status' => 'past_due']);

        Log::warning('Invoice payment failed for user: ' . $user->id, [
            'invoice_id' => $invoice->id,
            'amount' => $invoice->amount_due,
        ]);

        $this->sendInvoiceFailedNotification($user, $invoice);
    }

    /**
     * Handle charge refunded
     */
    protected function handleChargeRefunded($charge)
    {
        $user = $this->getUserFromCharge($charge);
        if (!$user) {
            return;
        }

        Log::info('Charge refunded for user: ' . $user->id, [
            'charge_id' => $charge->id,
            'amount' => $charge->amount_refunded,
        ]);

        $this->sendRefundNotification($user, $charge);
    }

    /**
     * Get user from payment intent
     */
    protected function getUserFromPaymentIntent($paymentIntent)
    {
        if (isset($paymentIntent->metadata->user_id)) {
            return User::find($paymentIntent->metadata->user_id);
        }

        if ($paymentIntent->customer) {
            return User::where('stripe_id', $paymentIntent->customer)->first();
        }

        return null;
    }

    /**
     * Get user from subscription
     */
    protected function getUserFromSubscription($subscription)
    {
        return User::where('stripe_id', $subscription->customer)->first();
    }

    /**
     * Get user from invoice
     */
    protected function getUserFromInvoice($invoice)
    {
        return User::where('stripe_id', $invoice->customer)->first();
    }

    /**
     * Get user from charge
     */
    protected function getUserFromCharge($charge)
    {
        return User::where('stripe_id', $charge->customer)->first();
    }

    /**
     * Send payment success notification
     */
    protected function sendPaymentSuccessNotification($user, $paymentIntent)
    {
        try {
            $user->notify(new \App\Notifications\PaymentSuccessNotification($paymentIntent));
        } catch (\Exception $e) {
            Log::error('Failed to send payment success notification: ' . $e->getMessage());
        }
    }

    /**
     * Send payment failed notification
     */
    protected function sendPaymentFailedNotification($user, $paymentIntent)
    {
        try {
            $user->notify(new \App\Notifications\PaymentFailedNotification($paymentIntent));
        } catch (\Exception $e) {
            Log::error('Failed to send payment failed notification: ' . $e->getMessage());
        }
    }

    /**
     * Send subscription created notification
     */
    protected function sendSubscriptionCreatedNotification($user, $subscription)
    {
        try {
            $user->notify(new \App\Notifications\SubscriptionCreatedNotification($subscription));
        } catch (\Exception $e) {
            Log::error('Failed to send subscription created notification: ' . $e->getMessage());
        }
    }

    /**
     * Send subscription past due notification
     */
    protected function sendSubscriptionPastDueNotification($user, $subscription)
    {
        try {
            $user->notify(new \App\Notifications\SubscriptionPastDueNotification($subscription));
        } catch (\Exception $e) {
            Log::error('Failed to send subscription past due notification: ' . $e->getMessage());
        }
    }

    /**
     * Send subscription cancelled notification
     */
    protected function sendSubscriptionCancelledNotification($user, $subscription)
    {
        try {
            $user->notify(new \App\Notifications\SubscriptionCancelledNotification($subscription));
        } catch (\Exception $e) {
            Log::error('Failed to send subscription cancelled notification: ' . $e->getMessage());
        }
    }

    /**
     * Send invoice failed notification
     */
    protected function sendInvoiceFailedNotification($user, $invoice)
    {
        try {
            $user->notify(new \App\Notifications\InvoiceFailedNotification($invoice));
        } catch (\Exception $e) {
            Log::error('Failed to send invoice failed notification: ' . $e->getMessage());
        }
    }

    /**
     * Send refund notification
     */
    protected function sendRefundNotification($user, $charge)
    {
        try {
            $user->notify(new \App\Notifications\RefundNotification($charge));
        } catch (\Exception $e) {
            Log::error('Failed to send refund notification: ' . $e->getMessage());
        }
    }
}
