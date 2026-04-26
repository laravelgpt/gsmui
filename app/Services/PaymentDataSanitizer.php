<?php

namespace App\Services;

/**
 * Payment Data Sanitizer
 */
class PaymentDataSanitizer
{
    /**
     * Sensitive fields that should not be logged
     */
    protected $sensitiveFields = [
        'card_number',
        'cvv',
        'expiry',
        'password',
        'token',
        'secret',
        'pin',
    ];
    
    /**
     * Sanitize payment data for logging
     */
    public function sanitizeForLog($data)
    {
        $sanitized = $data;
        
        foreach ($this->sensitiveFields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = '[REDACTED]';
            }
            
            // Also check nested fields
            foreach ($sanitized as $key => $value) {
                if (is_array($value) && isset($value[$field])) {
                    $sanitized[$key][$field] = '[REDACTED]';
                }
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Check if data contains sensitive information
     */
    public function containsSensitiveData($data)
    {
        foreach ($this->sensitiveFields as $field) {
            if (is_array($data)) {
                if (array_key_exists($field, $data)) {
                    return true;
                }
                foreach ($data as $value) {
                    if (is_array($value) && array_key_exists($field, $value)) {
                        return true;
                    }
                }
            } elseif (is_string($data) && stripos($data, $field) !== false) {
                return true;
            }
        }
        
        return false;
    }
}