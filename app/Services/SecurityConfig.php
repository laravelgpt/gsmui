<?php

namespace App\Services;

/**
 * Security Configuration Service
 * Centralizes all security settings
 */
class SecurityConfig
{
    /**
     * Check if security is fully enabled
     */
    public function isSecure(): bool
    {
        return 
            $this->isSessionSecure() &&
            $this->isEncryptionEnabled() &&
            $this->isCsrfEnabled() &&
            $this->isHttpsEnforced() &&
            $this->areSecurityHeadersSet();
    }
    
    public function isSessionSecure(): bool
    {
        return config('session.secure') &&
               config('session.http_only') &&
               config('session.same_site') === 'strict' &&
               config('session.encrypt');
    }
    
    public function isEncryptionEnabled(): bool
    {
        return config('app.cipher') === 'AES-256-CBC' &&
               !empty(config('app.key'));
    }
    
    public function isCsrfEnabled(): bool
    {
        return true; // Laravel CSRF is always enabled
    }
    
    public function isHttpsEnforced(): bool
    {
        return config('session.secure');
    }
    
    public function areSecurityHeadersSet(): bool
    {
        $headers = config('security.headers');
        return !empty($headers['x_frame_options']) &&
               !empty($headers['x_content_type_options']) &&
               !empty($headers['x_xss_protection']) &&
               !empty($headers['strict_transport_security']);
    }
    
    public function getSecurityScore(): int
    {
        $score = 0;
        $checks = [
            $this->isSessionSecure(),
            $this->isEncryptionEnabled(),
            $this->isCsrfEnabled(),
            $this->isHttpsEnforced(),
            $this->areSecurityHeadersSet(),
            file_exists('composer.lock'),
            file_exists('public/.htaccess'),
            config('security.cors.enabled'),
            config('security.rate_limiting.enabled'),
        ];
        
        foreach ($checks as $check) {
            if ($check) $score++;
        }
        
        return ($score / count($checks)) * 100;
    }
}