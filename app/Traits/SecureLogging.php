<?php

namespace App\Traits;

use App\Services\PaymentDataSanitizer;

/**
 * Secure Logging Trait
 */
trait SecureLogging
{
    /**
     * Log data securely (redacting sensitive information)
     */
    protected function secureLog(string $channel, string $message, array $data = [])
    {
        $sanitizer = new PaymentDataSanitizer();
        $sanitized = $sanitizer->sanitizeForLog($data);
        
        \Log::channel($channel)->info($message, $sanitized);
    }
    
    /**
     * Check if data contains sensitive information
     */
    protected function hasSensitiveData($data): bool
    {
        $sanitizer = new PaymentDataSanitizer();
        return $sanitizer->containsSensitiveData($data);
    }
}