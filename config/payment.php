
<?php

return [

    /**
     * Default Payment Gateway
     */
    'default' => env('PAYMENT_DEFAULT_GATEWAY', 'stripe'),

    /**
     * Fallback Gateway
     */
    'fallback' => env('PAYMENT_FALLBACK_GATEWAY', 'stripe'),

    /**
     * Payment Gateways Configuration
     * 
     * Supports 60+ payment gateways worldwide
     */
    'gateways' => [

        /**
         * Bangladesh Payment Gateways
         * Local payment methods for Bangladeshi market
         */
        'bkash' => env('BKASH_ENABLED', false) ? [
            'enabled' => true,
            'environment' => env('BKASH_ENVIRONMENT', 'sandbox'),
            'api_key' => env('BKASH_API_KEY'),
            'api_secret' => env('BKASH_API_SECRET'),
            'currency' => 'BDT',
            'features' => ['payment', 'refund', 'query', 'payout', 'recurring'],
        ] : ['enabled' => false],

        'nagad' => env('NAGAD_ENABLED', false) ? [
            'enabled' => true,
            'environment' => env('NAGAD_ENVIRONMENT', 'sandbox'),
            'api_key' => env('NAGAD_API_KEY'),
            'api_secret' => env('NAGAD_API_SECRET'),
            'currency' => 'BDT',
            'features' => ['payment', 'refund', 'verification', 'recurring', 'otp_based'],
        ] : ['enabled' => false],

        'rocket' => env('ROCKET_ENABLED', false) ? [
            'enabled' => true,
            'currency' => 'BDT',
            'features' => ['wallet', 'payment', 'transfer'],
        ] : ['enabled' => false],

        'upay' => env('UPAY_ENABLED', false) ? [
            'enabled' => true,
            'currency' => 'BDT',
            'features' => ['payment', 'refund', 'wallet'],
        ] : ['enabled' => false],

        'okwallet' => env('OKWALLET_ENABLED', false) ? [
            'enabled' => true,
            'currency' => 'BDT',
            'features' => ['wallet', 'payment', 'transfer'],
        ] : ['enabled' => false],

        'portwallet' => env('PORTWALLET_ENABLED', false) ? [
            'enabled' => true,
            'currency' => 'BDT',
            'features' => ['wallet', 'payment', 'card'],
        ] : ['enabled' => false],

        'surecash' => env('SURECASH_ENABLED', false) ? [
            'enabled' => true,
            'currency' => 'BDT',
            'features' => ['mobile_banking', 'payment', 'transfer'],
        ] : ['enabled' => false],

        'dbbl_nexus' => env('DBBL_NEXUS_ENABLED', false) ? [
            'enabled' => true,
            'currency' => 'BDT',
            'features' => ['card', 'internet_banking', 'mobile_banking'],
        ] : ['enabled' => false],

        /**
         * Stripe - Primary Gateway
         * https://stripe.com
         */
        'stripe' => [
            'enabled' => env('STRIPE_ENABLED', true),
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'currency' => env('STRIPE_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY'],
            'features' => ['cards', 'subscriptions', 'sepa', 'ach', 'alipay', 'wechat'],
        ],

        /**
         * PayPal
         * https://paypal.com
         */
        'paypal' => [
            'enabled' => env('PAYPAL_ENABLED', true),
            'mode' => env('PAYPAL_MODE', 'sandbox'),
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
            'url' => env('PAYPAL_URL', 'https://api-m.sandbox.paypal.com'),
            'currency' => env('PAYPAL_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
            'features' => ['paypal', 'credit_cards', 'venmo', 'paylater'],
        ],

        /**
         * PayPal - Business 2 Business (B2B)
         */
        'paypal_b2b' => env('PAYPAL_B2B_ENABLED', false) ? [
            'enabled' => true,
            'mode' => env('PAYPAL_B2B_MODE', 'sandbox'),
            'client_id' => env('PAYPAL_B2B_CLIENT_ID'),
            'secret' => env('PAYPAL_B2B_SECRET'),
            'currency' => 'USD',
            'features' => ['b2b_invoices', 'b2b_payments', 'supplier_payments', 'mass_payments', 'payouts', 'invoicing', 'recurring_billing', 'payment_terms', 'credit_applications', 'ap_automation', 'ar_automation', 'expense_management', 'virtual_cards', 'working_capital'],
        ] : ['enabled' => false],

        /**
         * PayPal - Peer to Peer (P2P)
         */
        'paypal_p2p' => env('PAYPAL_P2P_ENABLED', false) ? [
            'enabled' => true,
            'mode' => env('PAYPAL_P2P_MODE', 'sandbox'),
            'client_id' => env('PAYPAL_P2P_CLIENT_ID'),
            'secret' => env('PAYPAL_P2P_SECRET'),
            'currency' => 'USD',
            'features' => ['send_money', 'request_money', 'split_bill', 'pool_money', 'pay_contacts', 'qr_payments', 'cash_back', 'rewards', 'crypto_buy_sell'],
        ] : ['enabled' => false],

        /**
         * Razorpay (India)
         * https://razorpay.com
         */
        'razorpay' => [
            'enabled' => env('RAZORPAY_ENABLED', true),
            'key' => env('RAZORPAY_KEY'),
            'secret' => env('RAZORPAY_SECRET'),
            'currency' => env('RAZORPAY_CURRENCY', 'INR'),
            'supported_currencies' => ['INR'],
            'features' => ['cards', 'netbanking', 'upi', 'wallet'],
        ],

        /**
         * Paystack (Nigeria)
         * https://paystack.com
         */
        'paystack' => [
            'enabled' => env('PAYSTACK_ENABLED', true),
            'public_key' => env('PAYSTACK_PUBLIC_KEY'),
            'secret' => env('PAYSTACK_SECRET_KEY'),
            'currency' => env('PAYSTACK_CURRENCY', 'NGN'),
            'supported_currencies' => ['NGN', 'GHS', 'KES', 'ZAR'],
            'features' => ['cards', 'bank_transfer', 'ussd', 'mobile_money'],
        ],

        /**
         * Flutterwave (Africa)
         * https://flutterwave.com
         */
        'flutterwave' => [
            'enabled' => env('FLUTTERWAVE_ENABLED', true),
            'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
            'secret' => env('FLUTTERWAVE_SECRET_KEY'),
            'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
            'currency' => env('FLUTTERWAVE_CURRENCY', 'USD'),
            'supported_currencies' => ['NGN', 'GHS', 'KES', 'ZAR', 'TZS', 'UGX', 'USD', 'EUR', 'GBP'],
            'features' => ['cards', 'bank_transfer', 'mobile_money', 'ussd'],
        ],

        /**
         * Mollie (Europe)
         * https://mollie.com
         */
        'mollie' => [
            'enabled' => env('MOLLIE_ENABLED', true),
            'api_key' => env('MOLLIE_API_KEY'),
            'profile_id' => env('MOLLIE_PROFILE_ID'),
            'currency' => env('MOLLIE_CURRENCY', 'EUR'),
            'supported_currencies' => ['EUR', 'USD', 'GBP', 'AUD', 'CAD', 'CHF'],
            'features' => ['cards', 'ideal', 'sepa', 'bancontact', 'sofort', 'giropay', 'eps'],
        ],

        /**
         * Square
         * https://square.com
         */
        'square' => [
            'enabled' => env('SQUARE_ENABLED', false),
            'application_id' => env('SQUARE_APPLICATION_ID'),
            'access_token' => env('SQUARE_ACCESS_TOKEN'),
            'location_id' => env('SQUARE_LOCATION_ID'),
            'currency' => env('SQUARE_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'CAD', 'AUD', 'JPY', 'GBP', 'EUR'],
            'features' => ['cards', 'ach', 'digital_wallets'],
        ],

        /**
         * Braintree (PayPal)
         * https://braintreepayments.com
         */
        'braintree' => [
            'enabled' => env('BRAINTREE_ENABLED', false),
            'environment' => env('BRAINTREE_ENVIRONMENT', 'sandbox'),
            'merchant_id' => env('BRAINTREE_MERCHANT_ID'),
            'public_key' => env('BRAINTREE_PUBLIC_KEY'),
            'private_key' => env('BRAINTREE_PRIVATE_KEY'),
            'currency' => env('BRAINTREE_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
            'features' => ['cards', 'paypal', 'venmo', 'apple_pay', 'google_pay'],
        ],

        /**
         * Authorize.Net
         * https://authorize.net
         */
        'authorizenet' => [
            'enabled' => env('AUTHORIZENET_ENABLED', false),
            'api_login_id' => env('AUTHORIZENET_API_LOGIN_ID'),
            'transaction_key' => env('AUTHORIZENET_TRANSACTION_KEY'),
            'url' => env('AUTHORIZENET_URL', 'https://api.authorize.net/xml/v1/request.api'),
            'currency' => env('AUTHORIZENET_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
            'features' => ['cards', 'ach', 'e_check'],
        ],

        /**
         * Adyen
         * https://adyen.com
         */
        'adyen' => [
            'enabled' => env('ADYEN_ENABLED', false),
            'api_key' => env('ADYEN_API_KEY'),
            'merchant_account' => env('ADYEN_MERCHANT_ACCOUNT'),
            'client_key' => env('ADYEN_CLIENT_KEY'),
            'currency' => env('ADYEN_CURRENCY', 'EUR'),
            'supported_currencies' => ['EUR', 'USD', 'GBP', 'AUD', 'CAD', 'CHF'],
            'features' => ['cards', 'ideal', 'sepa', 'apple_pay', 'google_pay', 'paypal'],
        ],

        /**
         * Stripe Ideal (Netherlands)
         */
        'stripe-ideal' => env('STRIPE_IDEAL_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'EUR',
            'supported_currencies' => ['EUR'],
            'features' => ['ideal'],
        ] : ['enabled' => false],

        /**
         * Stripe Sofort (Europe)
         */
        'stripe-sofort' => env('STRIPE_SOFORT_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'EUR',
            'supported_currencies' => ['EUR'],
            'features' => ['sofort'],
        ] : ['enabled' => false],

        /**
         * Stripe Giropay (Germany)
         */
        'stripe-giropay' => env('STRIPE_GIROPAY_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'EUR',
            'supported_currencies' => ['EUR'],
            'features' => ['giropay'],
        ] : ['enabled' => false],

        /**
         * Stripe Bancontact (Belgium)
         */
        'stripe-bancontact' => env('STRIPE_BANCONTACT_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'EUR',
            'supported_currencies' => ['EUR'],
            'features' => ['bancontact'],
        ] : ['enabled' => false],

        /**
         * Stripe EPS (Austria)
         */
        'stripe-eps' => env('STRIPE_EPS_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'EUR',
            'supported_currencies' => ['EUR'],
            'features' => ['eps'],
        ] : ['enabled' => false],

        /**
         * Stripe Przelewy24 (Poland)
         */
        'stripe-przelewy24' => env('STRIPE_PRZELEWY24_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'EUR',
            'supported_currencies' => ['EUR', 'PLN'],
            'features' => ['przelewy24'],
        ] : ['enabled' => false],

        /**
         * Stripe Alipay (China)
         */
        'stripe-alipay' => env('STRIPE_ALIPAY_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'CNY',
            'supported_currencies' => ['CNY', 'USD', 'EUR'],
            'features' => ['alipay'],
        ] : ['enabled' => false],

        /**
         * Stripe WeChat Pay (China)
         */
        'stripe-wechat' => env('STRIPE_WECHAT_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'CNY',
            'supported_currencies' => ['CNY', 'USD', 'EUR'],
            'features' => ['wechat_pay'],
        ] : ['enabled' => false],

        /**
         * Stripe Klarna (Buy Now Pay Later)
         */
        'stripe-klarna' => env('STRIPE_KLARNA_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => env('STRIPE_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'SEK', 'NOK'],
            'features' => ['klarna', 'pay_later'],
        ] : ['enabled' => false],

        /**
         * Stripe Afterpay (Buy Now Pay Later)
         */
        'stripe-afterpay' => env('STRIPE_AFTERPAY_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'AUD',
            'supported_currencies' => ['AUD', 'NZD', 'USD', 'CAD'],
            'features' => ['afterpay', 'pay_later'],
        ] : ['enabled' => false],

        /**
         * Stripe Affirm (Buy Now Pay Later)
         */
        'stripe-affirm' => env('STRIPE_AFFIRM_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'USD',
            'supported_currencies' => ['USD', 'CAD'],
            'features' => ['affirm', 'pay_later'],
        ] : ['enabled' => false],

        /**
         * Stripe SEPA Direct Debit (Europe)
         */
        'stripe-sepa' => env('STRIPE_SEPA_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'EUR',
            'supported_currencies' => ['EUR'],
            'features' => ['sepa', 'direct_debit'],
        ] : ['enabled' => false],

        /**
         * Stripe P24 (Poland)
         */
        'stripe-p24' => env('STRIPE_P24_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'EUR',
            'supported_currencies' => ['EUR', 'PLN'],
            'features' => ['p24', 'bank_transfer'],
        ] : ['enabled' => false],

        /**
         * Stripe BECS Direct Debit (Australia)
         */
        'stripe-becs' => env('STRIPE_BEC_SES_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'AUD',
            'supported_currencies' => ['AUD'],
            'features' => ['becs', 'direct_debit'],
        ] : ['enabled' => false],

        /**
         * Stripe Australia BECS
         */
        'stripe-au-becs' => env('STRIPE_AU_BEC_SES_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'AUD',
            'supported_currencies' => ['AUD'],
            'features' => ['becs', 'direct_debit'],
        ] : ['enabled' => false],

        /**
         * Stripe ACH (USA)
         */
        'stripe-ach' => env('STRIPE_ACH_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'currency' => 'USD',
            'supported_currencies' => ['USD'],
            'features' => ['ach', 'bank_transfer'],
        ] : ['enabled' => false],

        /**
         * Checkout.com
         * https://checkout.com
         */
        'checkout-com' => env('CHECKOUT_COM_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('CHECKOUT_COM_API_KEY'),
            'secret' => env('CHECKOUT_COM_SECRET_KEY'),
            'currency' => env('CHECKOUT_COM_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'AUD', 'CAD', 'CHF'],
            'features' => ['cards', 'wallets', 'bank_transfers', 'ach'],
        ] : ['enabled' => false],

        /**
         * CCAvenue (India)
         * https://ccavenue.com
         */
        'ccavenue' => env('CCAVENUE_ENABLED', false) ? [
            'enabled' => true,
            'merchant_id' => env('CCAVENUE_MERCHANT_ID'),
            'encryption_key' => env('CCAVENUE_ENCRYPTION_KEY'),
            'currency' => env('CCAVENUE_CURRENCY', 'INR'),
            'supported_currencies' => ['INR'],
            'features' => ['cards', 'netbanking', 'upi', 'wallets'],
        ] : ['enabled' => false],

        /**
         * Instamojo (India)
         * https://instamojo.com
         */
        'instamojo' => env('INSTAMOJO_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('INSTAMOJO_API_KEY'),
            'auth_token' => env('INSTAMOJO_AUTH_TOKEN'),
            'currency' => env('INSTAMOJO_CURRENCY', 'INR'),
            'supported_currencies' => ['INR'],
            'features' => ['cards', 'upi', 'wallets', 'netbanking'],
        ] : ['enabled' => false],

        /**
         * Paytm (India)
         * https://paytm.com
         */
        'paytm' => env('PAYTM_ENABLED', false) ? [
            'enabled' => true,
            'merchant_id' => env('PAYTM_MERCHANT_ID'),
            'merchant_key' => env('PAYTM_MERCHANT_KEY'),
            'currency' => env('PAYTM_CURRENCY', 'INR'),
            'supported_currencies' => ['INR'],
            'features' => ['upi', 'wallets', 'cards', 'netbanking'],
        ] : ['enabled' => false],

        /**
         * PhonePe (India)
         * https://phonepe.com
         */
        'phonepe' => env('PHONEPE_ENABLED', false) ? [
            'enabled' => true,
            'merchant_id' => env('PHONEPE_MERCHANT_ID'),
            'salt_key' => env('PHONEPE_SALT_KEY'),
            'salt_index' => env('PHONEPE_SALT_INDEX'),
            'currency' => env('PHONEPE_CURRENCY', 'INR'),
            'supported_currencies' => ['INR'],
            'features' => ['upi', 'wallets', 'cards'],
        ] : ['enabled' => false],

        /**
         * Google Pay (Digital Wallet)
         */
        'googlepay' => env('GOOGLEPAY_ENABLED', false) ? [
            'enabled' => true,
            'merchant_id' => env('GOOGLEPAY_MERCHANT_ID'),
            'gateway_merchant_id' => env('GOOGLEPAY_GATEWAY_MERCHANT_ID'),
            'currency' => env('GOOGLEPAY_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'EUR', 'GBP'],
            'features' => ['digital_wallet', 'cards', 'bank_account'],
        ] : ['enabled' => false],

        /**
         * Apple Pay (Digital Wallet)
         */
        'applepay' => env('APPLEPAY_ENABLED', false) ? [
            'enabled' => true,
            'merchant_id' => env('APPLEPAY_MERCHANT_ID'),
            'certificate_path' => env('APPLEPAY_CERTIFICATE_PATH'),
            'currency' => env('APPLEPAY_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'AUD', 'CAD'],
            'features' => ['digital_wallet', 'cards'],
        ] : ['enabled' => false],

        /**
         * Samsung Pay (Digital Wallet)
         */
        'samsungpay' => env('SAMSUNGPAY_ENABLED', false) ? [
            'enabled' => true,
            'merchant_id' => env('SAMSUNGPAY_MERCHANT_ID'),
            'currency' => env('SAMSUNGPAY_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'KRW'],
            'features' => ['digital_wallet', 'cards'],
        ] : ['enabled' => false],

        /**
         * Venmo (USA)
         * https://venmo.com
         */
        'venmo' => env('VENMO_ENABLED', false) ? [
            'enabled' => true,
            'client_id' => env('VENMO_CLIENT_ID'),
            'secret' => env('VENMO_SECRET'),
            'access_token' => env('VENMO_ACCESS_TOKEN'),
            'currency' => env('VENMO_CURRENCY', 'USD'),
            'supported_currencies' => ['USD'],
            'features' => ['venmo', 'cards', 'bank_account'],
        ] : ['enabled' => false],

        /**
         * Cash App (USA)
         * https://cash.app
         */
        'cashapp' => env('CASHAPP_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('CASHAPP_API_KEY'),
            'access_token' => env('CASHAPP_ACCESS_TOKEN'),
            'currency' => env('CASHAPP_CURRENCY', 'USD'),
            'supported_currencies' => ['USD'],
            'features' => ['cash_app', 'cards'],
        ] : ['enabled' => false],

        /**
         * Zelle (USA)
         * https://zellepay.com
         */
        'zelle' => env('ZELLE_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('ZELLE_API_KEY'),
            'currency' => env('ZELLE_CURRENCY', 'USD'),
            'supported_currencies' => ['USD'],
            'features' => ['bank_transfer'],
        ] : ['enabled' => false],

        /**
         * Bitcoin (Cryptocurrency)
         */
        'bitcoin' => env('BITCOIN_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('BITCOIN_API_KEY'),
            'xpub' => env('BITCOIN_XPUB'),
            'currency' => env('BITCOIN_CURRENCY', 'BTC'),
            'supported_currencies' => ['BTC', 'USD', 'EUR'],
            'features' => ['bitcoin', 'lightning', 'blockchain'],
        ] : ['enabled' => false],

        /**
         * Ethereum (Cryptocurrency)
         */
        'ethereum' => env('ETHEREUM_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('ETHEREUM_API_KEY'),
            'contract_address' => env('ETHEREUM_CONTRACT_ADDRESS'),
            'currency' => env('ETHEREUM_CURRENCY', 'ETH'),
            'supported_currencies' => ['ETH', 'USD', 'EUR'],
            'features' => ['ethereum', 'erc20', 'smart_contracts'],
        ] : ['enabled' => false],

        /**
         * USDC (Stablecoin)
         */
        'usdc' => env('USDC_ENABLED', false) ? [
            'enabled' => true,
            'api_key' => env('USDC_API_KEY'),
            'contract_address' => env('USDC_CONTRACT_ADDRESS'),
            'currency' => env('USDC_CURRENCY', 'USDC'),
            'supported_currencies' => ['USDC', 'USD'],
            'features' => ['stablecoin', 'blockchain', 'erc20'],
        ] : ['enabled' => false],

        /**
         * PayPal Pro (Direct Credit Card)
         */
        'paypal-pro' => env('PAYPAL_PRO_ENABLED', false) ? [
            'enabled' => true,
            'api_username' => env('PAYPAL_PRO_API_USERNAME'),
            'api_password' => env('PAYPAL_PRO_API_PASSWORD'),
            'api_signature' => env('PAYPAL_PRO_API_SIGNATURE'),
            'currency' => env('PAYPAL_PRO_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
            'features' => ['direct_credit_card', 'cards'],
        ] : ['enabled' => false],

        /**
         * PayPal Payflow
         */
        'paypal-payflow' => env('PAYPAL_PAYFLOW_ENABLED', false) ? [
            'enabled' => true,
            'partner' => env('PAYPAL_PAYFLOW_PARTNER'),
            'vendor' => env('PAYPAL_PAYFLOW_VENDOR'),
            'user' => env('PAYPAL_PAYFLOW_USER'),
            'password' => env('PAYPAL_PAYFLOW_PASSWORD'),
            'currency' => env('PAYPAL_PAYFLOW_CURRENCY', 'USD'),
            'supported_currencies' => ['USD', 'EUR', 'GBP'],
            'features' => ['credit_card', 'cards', 'recurring'],
        ] : ['enabled' => false],

        /**
         * Worldpay
         * https://worldpay.com
         */
        'worldpay' => env('WORLDPAY_ENABLED', false) ? [
            'enabled' => true,
            'service_key' => env('WORLDPAY_SERVICE_KEY'),
            'client_key' => env('WORLDPAY_CLIENT_KEY'),
            'currency' => env('WORLDPAY_CURRENCY', 'GBP'),
            'supported_currencies' => ['GBP', 'USD', 'EUR'],
            'features' => ['cards', 'wallets', 'paypal'],
        ] : ['enabled' => false],

        /**
         * Sage Pay
         * https://sagepay.com
         */
        'sagepay' => '<response clipped><NOTE>Observations should not exceeded 100000 characters. 30084 characters were elided. Please try a different command that produces less output or use head/tail/grep/redirect the output to a file. Do not use interactive pagers.</NOTE>}