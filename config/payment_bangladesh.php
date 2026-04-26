
<?php

return [

    /**
     * Bangladesh Payment Gateways
     * Local payment methods for Bangladeshi market
     */
    'bangladesh' => [

        /**
         * bKash - Bangladesh's leading mobile financial service
         * https://bkash.com
         */
        'bkash' => [
            'enabled' => env('BKASH_ENABLED', true),
            'environment' => env('BKASH_ENVIRONMENT', 'sandbox'), // sandbox or production
            'api_key' => env('BKASH_API_KEY'),
            'api_secret' => env('BKASH_API_SECRET'),
            'username' => env('BKASH_USERNAME'),
            'password' => env('BKASH_PASSWORD'),
            'app_key' => env('BKASH_APP_KEY'),
            'app_secret' => env('BKASH_APP_SECRET'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT', 'USD'],
            'min_amount' => 10,
            'max_amount' => 500000,
            'features' => [
                'payment',
                'refund', 
                'query',
                'payout',
                'recurring',
            ],
        ],

        /**
         * Nagad - Fast mobile financial service
         * https://nagad.com.bd
         */
        'nagad' => [
            'enabled' => env('NAGAD_ENABLED', true),
            'environment' => env('NAGAD_ENVIRONMENT', 'sandbox'), // sandbox or production
            'api_key' => env('NAGAD_API_KEY'),
            'api_secret' => env('NAGAD_API_SECRET'),
            'merchant_id' => env('NAGAD_MERCHANT_ID'),
            'merchant_number' => env('NAGAD_MERCHANT_NUMBER'),
            'callback_url' => env('NAGAD_CALLBACK_URL'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'min_amount' => 10,
            'max_amount' => 500000,
            'features' => [
                'payment',
                'refund',
                'verification',
                'recurring',
                'otp_based',
            ],
        ],

        /**
         * bKash - Alternative configuration
         */
        'bkash_v2' => [
            'enabled' => env('BKASH_V2_ENABLED', false),
            'token' => env('BKASH_V2_TOKEN'),
            'merchant_id' => env('BKASH_V2_MERCHANT_ID'),
            'password' => env('BKASH_V2_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT', 'USD'],
            'features' => ['payment', 'refund'],
        ],

        /**
         * Nagad - Alternative configuration
         */
        'nagad_v2' => [
            'enabled' => env('NAGAD_V2_ENABLED', false),
            'api_key' => env('NAGAD_V2_API_KEY'),
            'api_secret' => env('NAGAD_V2_API_SECRET'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['payment', 'refund'],
        ],

        /**
         * Rocket (bKash-based wallet)
         */
        'rocket' => [
            'enabled' => env('ROCKET_ENABLED', false),
            'api_key' => env('ROCKET_API_KEY'),
            'api_secret' => env('ROCKET_API_SECRET'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['wallet', 'payment', 'transfer'],
        ],

        /**
         * Upay (United Commercial Bank)
         */
        'upay' => [
            'enabled' => env('UPAY_ENABLED', false),
            'merchant_id' => env('UPAY_MERCHANT_ID'),
            'password' => env('UPAY_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['payment', 'refund', 'wallet'],
        ],

        /**
         * OK Wallet
         */
        'okwallet' => [
            'enabled' => env('OKWALLET_ENABLED', false),
            'api_key' => env('OKWALLET_API_KEY'),
            'api_secret' => env('OKWALLET_API_SECRET'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['wallet', 'payment', 'transfer'],
        ],

        /**
         * Port Wallet
         */
        'portwallet' => [
            'enabled' => env('PORTWALLET_ENABLED', false),
            'api_key' => env('PORTWALLET_API_KEY'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['wallet', 'payment', 'card'],
        ],

        /**
         * SureCash
         */
        'surecash' => [
            'enabled' => env('SURECASH_ENABLED', false),
            'api_key' => env('SURECASH_API_KEY'),
            'api_secret' => env('SURECASH_API_SECRET'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['mobile_banking', 'payment', 'transfer'],
        ],

        /**
         * DBBL Nexus (Dutch-Bangla Bank)
         */
        'dbbl_nexus' => [
            'enabled' => env('DBBL_NEXUS_ENABLED', false),
            'merchant_id' => env('DBBL_MERCHANT_ID'),
            'password' => env('DBBL_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking', 'mobile_banking'],
        ],

        /**
         * CityTouch (City Bank)
         */
        'citytouch' => [
            'enabled' => env('CITYTOUCH_ENABLED', false),
            'merchant_id' => env('CITYTOUCH_MERCHANT_ID'),
            'password' => env('CITYTOUCH_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking'],
        ],

        /**
         * QCash (Eastern Bank)
         */
        'qcash' => [
            'enabled' => env('QCASH_ENABLED', false),
            'merchant_id' => env('QCASH_MERCHANT_ID'),
            'password' => env('QCASH_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking', 'mobile_banking'],
        ],

        /**
         * iPay (Islamic Bank)
         */
        'ipay' => [
            'enabled' => env('IPAY_ENABLED', false),
            'merchant_id' => env('IPAY_MERCHANT_ID'),
            'password' => env('IPAY_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking', 'mobile_banking'],
        ],

        /**
         * BRAC Bank Payment Gateway
         */
        'brac' => [
            'enabled' => env('BRAC_ENABLED', false),
            'merchant_id' => env('BRAC_MERCHANT_ID'),
            'password' => env('BRAC_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking', 'mobile_banking'],
        ],

        /**
         * Mutual Trust Bank (MTB) Nexus
         */
        'mtb_nexus' => [
            'enabled' => env('MTB_NEXUS_ENABLED', false),
            'merchant_id' => env('MTB_MERCHANT_ID'),
            'password' => env('MTB_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking'],
        ],

        /**
         * Southeast Bank Payment Gateway
         */
        'southeast' => [
            'enabled' => env('SOUTHEAST_ENABLED', false),
            'merchant_id' => env('SOUTHEAST_MERCHANT_ID'),
            'password' => env('SOUTHEAST_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking'],
        ],

        /**
         * Prime Bank Payment Gateway
         */
        'prime' => [
            'enabled' => env('PRIME_ENABLED', false),
            'merchant_id' => env('PRIME_MERCHANT_ID'),
            'password' => env('PRIME_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking', 'mobile_banking'],
        ],

        /**
         * EBL (Eastern Bank Limited) Payment Gateway
         */
        'ebl' => [
            'enabled' => env('EBL_ENABLED', false),
            'merchant_id' => env('EBL_MERCHANT_ID'),
            'password' => env('EBL_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking', 'mobile_banking'],
        ],

        /**
         * Social Islami Bank (SSIBL) Payment Gateway
         */
        'sibl' => [
            'enabled' => env('SIBL_ENABLED', false),
            'merchant_id' => env('SIBL_MERCHANT_ID'),
            'password' => env('SIBL_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking', 'mobile_banking'],
        ],

        /**
         * Al-Arafah Islami Bank Payment Gateway
         */
        'al_arafah' => [
            'enabled' => env('AL_ARAFAH_ENABLED', false),
            'merchant_id' => env('AL_ARAFAH_MERCHANT_ID'),
            'password' => env('AL_ARAFAH_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking', 'mobile_banking'],
        ],

        /**
         * Islami Bank Bangladesh Payment Gateway
         */
        'islami_bank' => [
            'enabled' => env('ISLAMI_BANK_ENABLED', false),
            'merchant_id' => env('ISLAMI_BANK_MERCHANT_ID'),
            'password' => env('ISLAMI_BANK_PASSWORD'),
            'currency' => 'BDT',
            'supported_currencies' => ['BDT'],
            'features' => ['card', 'internet_banking', 'mobile_banking'],
        ],

    ],

    /**
     * Binance - Cryptocurrency Exchange
     * https://binance.com
     */
    'binance' => [
        'enabled' => env('BINANCE_ENABLED', true),
        'api_key' => env('BINANCE_API_KEY'),
        'secret_key' => env('BINANCE_SECRET_KEY'),
        'testnet' => env('BINANCE_TESTNET', false),
        'base_url' => env('BINANCE_BASE_URL', 'https://api.binance.com'),
        'websocket_url' => env('BINANCE_WEBSOCKET_URL', 'wss://stream.binance.com:9443'),
        'supported_coins' => [
            'BTC', 'ETH', 'BNB', 'ADA', 'XRP', 'SOL', 'DOT', 'DOGE', 'AVAX', 'MATIC',
            'LTC', 'BCH', 'LINK', 'ATOM', 'XLM', 'ALGO', 'VET', 'ICP', 'FIL', 'TRX',
            'ETC', 'XMR', 'EOS', 'AAVE', 'UNI', 'CAKE', 'FTM', 'NEAR', 'GRT', 'SAND',
            'MANA', 'AXS', 'CHZ', 'ENJ', 'BAT', 'ZEC', 'DASH', 'COMP', 'MKR', 'SNX',
        ],
        'features' => [
            'spot_trading',
            'futures_trading',
            'margin_trading',
            'staking',
            'savings',
            'p2p_trading',
            'deposit',
            'withdrawal',
            'recurring_buy',
            'crypto_convert',
        ],
        'fees' => [
            'spot_maker' => 0.001, // 0.1%
            'spot_taker' => 0.001, // 0.1%
            'futures_maker' => 0.0002, // 0.02%
            'futures_taker' => 0.0004, // 0.04%
            'withdrawal' => 'variable',
        ],
        'limits' => [
            'min_deposit' => 10, // USD equivalent
            'max_deposit' => 1000000, // USD equivalent
            'min_withdrawal' => 10, // USD equivalent
            'max_withdrawal' => 100000, // USD equivalent
        ],
    ],

    /**
     * PayPal - Business 2 Business (B2B)
     * https://paypal.com/business
     */
    'paypal_b2b' => [
        'enabled' => env('PAYPAL_B2B_ENABLED', true),
        'mode' => env('PAYPAL_B2B_MODE', 'sandbox'),
        'client_id' => env('PAYPAL_B2B_CLIENT_ID'),
        'secret' => env('PAYPAL_B2B_SECRET'),
        'merchant_id' => env('PAYPAL_B2B_MERCHANT_ID'),
        'currency' => env('PAYPAL_B2B_CURRENCY', 'USD'),
        'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY'],
        'features' => [
            'b2b_invoices',
            'b2b_payments',
            'supplier_payments',
            'mass_payments',
            'payouts',
            'invoicing',
            'recurring_billing',
            'payment_terms',
            'credit_applications',
        ],
        'b2b_features' => [
            'ap_automation', // Accounts Payable
            'ar_automation', // Accounts Receivable
            'expense_management',
            'virtual_cards',
            'working_capital',
        ],
    ],

    /**
     * PayPal - Peer to Peer (P2P)
     * https://paypal.com/p2p
     */
    'paypal_p2p' => [
        'enabled' => env('PAYPAL_P2P_ENABLED', true),
        'mode' => env('PAYPAL_P2P_MODE', 'sandbox'),
        'client_id' => env('PAYPAL_P2P_CLIENT_ID'),
        'secret' => env('PAYPAL_P2P_SECRET'),
        'currency' => env('PAYPAL_P2P_CURRENCY', 'USD'),
        'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
        'features' => [
            'send_money',
            'request_money',
            'split_bill',
            'pool_money',
            'pay_contacts',
            'qr_payments',
            'cash_back',
            'rewards',
            'crypto_buy_sell',
        ],
        'limits' => [
            'personal_send_limit' => 60000, // per month USD
            'business_receive_limit' => 250000, // per month USD
        ],
    ],

    /**
     * Mercury - US Business Banking
     */
    'mercury' => [
        'enabled' => env('MERCURY_ENABLED', false),
        'api_key' => env('MERCURY_API_KEY'),
        'client_id' => env('MERCURY_CLIENT_ID'),
        'currency' => 'USD',
        'features' => ['business_banking', 'payments', 'api'],
    ],

    /**
     * Wise (formerly TransferWise) - International Transfers
     */
    'wise' => [
        'enabled' => env('WISE_ENABLED', false),
        'api_key' => env('WISE_API_KEY'),
        'profile_id' => env('WISE_PROFILE_ID'),
        'features' => ['international_transfers', 'borderless_accounts', 'multi_currency'],
    ],

];
