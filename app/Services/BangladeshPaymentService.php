<?php

namespace App\Services;

use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Bangladesh Payment Service
 * Handles local Bangladeshi payment gateways
 */
class BangladeshPaymentService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('payment_bangladesh');
    }

    /**
     * Process bKash payment
     */
    public function processBkashPayment(User $user, $amount, $currency = 'BDT', $transactionId = null)
    {
        $config = $this->config['bangladesh']['bkash'] ?? [];

        if (!$config['enabled']) {
            return ['success' => false, 'error' => 'bKash payment not enabled'];
        }

        try {
            // Get token
            $tokenResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'username' => $config['username'],
                'password' => $config['password'],
            ])->post($config['environment'] === 'production'
                ? 'https://tokenized.pay.bka.sh/v1.2.0/tokenized/authenticate'
                : 'https://tokenized.sandbox.bka.sh/v1.2.0/tokenized/authenticate', [
                'app_key' => $config['app_key'],
                'app_secret' => $config['app_secret'],
            ]);

            if (!$tokenResponse->successful()) {
                return ['success' => false, 'error' => 'Failed to authenticate with bKash'];
            }

            $token = $tokenResponse->json('id_token');

            // Create payment
            $paymentResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'X-APP-Key' => $config['app_key'],
            ])->post($config['environment'] === 'production'
                ? 'https://tokenized.pay.bka.sh/v1.2.0/tokenized/pay'
                : 'https://tokenized.sandbox.bka.sh/v1.2.0/tokenized/pay', [
                'mode' => '0011',
                'payerReference' => $user->phone ?? $user->id,
                'payeeReference' => $transactionId ?? uniqid(),
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => $currency,
                'intent' => 'sale',
                'merchantInvoiceNumber' => 'INV-' . uniqid(),
            ]);

            if ($paymentResponse->successful()) {
                $data = $paymentResponse->json();
                return [
                    'success' => true,
                    'gateway' => 'bkash',
                    'transaction_id' => $data['trxID'] ?? null,
                    'status' => $data['transactionStatus'] ?? 'pending',
                    'amount' => $amount,
                    'currency' => $currency,
                ];
            }

            return ['success' => false, 'error' => 'bKash payment failed', 'details' => $paymentResponse->json()];

        } catch (\Exception $e) {
            Log::error('bKash payment error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage(), 'gateway' => 'bkash'];
        }
    }

    /**
     * Process Nagad payment
     */
    public function processNagadPayment(User $user, $amount, $currency = 'BDT', $transactionId = null)
    {
        $config = $this->config['bangladesh']['nagad'] ?? [];

        if (!$config['enabled']) {
            return ['success' => false, 'error' => 'Nagad payment not enabled'];
        }

        try {
            // Generate signature
            $timestamp = date('YmdHis');
            $randomString = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
            $merchantId = $config['merchant_id'];
            $invoiceNumber = 'INV-' . uniqid();

            $signatureString = $merchantId . $timestamp . $randomString . $invoiceNumber . $amount . $currency;
            $signature = hash_hmac('sha256', $signatureString, $config['api_secret'], true);
            $signatureBase64 = base64_encode($signature);

            // Create payment
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-KEY' => $config['api_key'],
                'X-SIGNATURE' => $signatureBase64,
                'X-TIMESTAMP' => $timestamp,
                'X-RANDOM-STRING' => $randomString,
            ])->post($config['environment'] === 'production'
                ? 'https://api.partner.nagad.com.cn/v1/merchant/biash/payment/request'
                : 'https://sandbox.api.partner.nagad.com.cn/v1/merchant/biash/payment/request', [
                'merchantId' => $merchantId,
                'merchantNumber' => $config['merchant_number'],
                'invoiceNumber' => $invoiceNumber,
                'amount' => $amount,
                'currency' => $currency,
                'description' => 'Payment for product/service',
                'customerMobile' => $user->phone ?? '',
                'customerName' => $user->name,
                'customerEmail' => $user->email,
                'customerAddress' => $user->address ?? '',
                'orderId' => $transactionId ?? uniqid(),
                'clientIp' => request()->ip(),
                'terminalId' => 'WEB',
                'callbackUrl' => $config['callback_url'] ?? route('payment.callback'),
                'websiteUrl' => config('app.url'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'gateway' => 'nagad',
                    'payment_url' => $data['callBackUrl'] ?? null,
                    'reference' => $data['referenceId'] ?? null,
                    'amount' => $amount,
                    'currency' => $currency,
                ];
            }

            return ['success' => false, 'error' => 'Nagad payment failed', 'details' => $response->json()];

        } catch (\Exception $e) {
            Log::error('Nagad payment error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage(), 'gateway' => 'nagad'];
        }
    }

    /**
     * Process Rocket (bKash wallet) payment
     */
    public function processRocketPayment(User $user, $amount, $currency = 'BDT')
    {
        return ['success' => false, 'error' => 'Rocket payment integration pending'];
    }

    /**
     * Process Upay payment
     */
    public function processUpayPayment(User $user, $amount, $currency = 'BDT')
    {
        return ['success' => false, 'error' => 'Upay payment integration pending'];
    }

    /**
     * Process any Bangladesh payment method
     */
    public function processBangladeshPayment(User $user, $amount, $method, $currency = 'BDT', $orderData = [])
    {
        switch ($method) {
            case 'bkash':
                return $this->processBkashPayment($user, $amount, $currency, $orderData['transaction_id'] ?? null);
            case 'nagad':
                return $this->processNagadPayment($user, $amount, $currency, $orderData['transaction_id'] ?? null);
            case 'rocket':
                return $this->processRocketPayment($user, $amount, $currency);
            case 'upay':
                return $this->processUpayPayment($user, $amount, $currency);
            default:
                return ['success' => false, 'error' => 'Unknown payment method: ' . $method];
        }
    }

    /**
     * Verify bKash transaction
     */
    public function verifyBkashTransaction($transactionId)
    {
        $config = $this->config['bangladesh']['bkash'] ?? [];

        try {
            // Get token
            $tokenResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'username' => $config['username'],
                'password' => $config['password'],
            ])->post($config['environment'] === 'production'
                ? 'https://tokenized.pay.bka.sh/v1.2.0/tokenized/authenticate'
                : 'https://tokenized.sandbox.bka.sh/v1.2.0/tokenized/authenticate', [
                'app_key' => $config['app_key'],
                'app_secret' => $config['app_secret'],
            ]);

            if (!$tokenResponse->successful()) {
                return ['success' => false, 'error' => 'Failed to authenticate'];
            }

            $token = $tokenResponse->json('id_token');

            // Query transaction
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'X-APP-Key' => $config['app_key'],
            ])->get($config['environment'] === 'production'
                ? "https://tokenized.pay.bka.sh/v1.2.0/tokenized/query/{$transactionId}"
                : "https://tokenized.sandbox.bka.sh/v1.2.0/tokenized/query/{$transactionId}");

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => 'Transaction not found'];

        } catch (\Exception $e) {
            Log::error('bKash verification error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get available Bangladesh payment methods
     */
    public function getAvailableMethods()
    {
        $methods = [];
        $bangladeshConfig = $this->config['bangladesh'] ?? [];

        foreach ($bangladeshConfig as $method => $config) {
            if ($config['enabled'] ?? false) {
                $methods[$method] = [
                    'name' => strtoupper($method),
                    'currency' => $config['currency'] ?? 'BDT',
                    'min_amount' => $config['min_amount'] ?? 10,
                    'max_amount' => $config['max_amount'] ?? 500000,
                    'features' => $config['features'] ?? [],
                ];
            }
        }

        return $methods;
    }
}
