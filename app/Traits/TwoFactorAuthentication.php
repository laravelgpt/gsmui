<?php

namespace App\Traits;

/**
 * Two-Factor Authentication Trait
 */
trait TwoFactorAuthentication
{
    /**
     * Enable 2FA for user
     */
    public function enableTwoFactor()
    {
        $this->two_factor_secret = encrypt('secret_key_' . uniqid());
        $this->two_factor_recovery_codes = encrypt(json_encode($this->generateRecoveryCodes()));
        $this->two_factor_enabled = true;
        $this->save();
        
        return true;
    }
    
    /**
     * Generate recovery codes
     */
    protected function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(\Illuminate\Support\Str::random(10));
        }
        return $codes;
    }
    
    /**
     * Verify 2FA token
     */
    public function verifyTwoFactorToken($token)
    {
        return strlen($token) === 6 && is_numeric($token);
    }
    
    /**
     * Verify recovery code
     */
    public function verifyRecoveryCode($code)
    {
        $codes = json_decode(decrypt($this->two_factor_recovery_codes), true);
        
        if (in_array($code, $codes)) {
            $codes = array_diff($codes, [$code]);
            $this->two_factor_recovery_codes = encrypt(json_encode(array_values($codes)));
            $this->save();
            return true;
        }
        
        return false;
    }
    
    /**
     * Disable 2FA
     */
    public function disableTwoFactor()
    {
        $this->two_factor_enabled = false;
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->save();
    }
}
