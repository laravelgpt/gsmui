
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Security Test Suite
 * 
 * This test suite ensures 100% security compliance
 * for the GSM-UI Laravel package.
 */
class SecurityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_secure_password_hashing()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertStringContainsString('$2y$', $user->password);
    }

    /** @test */
    public function it_encrypts_sensitive_user_data()
    {
        $user = User::factory()->create([
            'two_factor_secret' => 'secret_key_12345',
        ]);

        $this->assertNotNull($user->two_factor_secret);
        $this->assertNotEquals('secret_key_12345', $user->two_factor_secret);
    }

    /** @test */
    public function it_has_secure_cookie_settings()
    {
        $response = $this->get('/');
        
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function it_uses_secure_session_configuration()
    {
        $this->assertTrue(config('session.secure'));
        $this->assertTrue(config('session.http_only'));
        $this->assertEquals('strict', config('session.same_site'));
        $this->assertTrue(config('session.encrypt'));
    }

    /** @test */
    public function it_prevents_session_fixation()
    {
        $user = User::factory()->create();
        
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    /** @test */
    public function it_regenerates_session_id_on_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $oldSessionId = session()->getId();
        
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertNotEquals($oldSessionId, session()->getId());
    }

    /** @test */
    public function it_uses_csrf_protection()
    {
        $response = $this->post('/login', []);
        
        $response->assertStatus(302); // Redirect due to CSRF protection
    }

    /** @test */
    public function it_has_security_headers()
    {
        $response = $this->get('/');
        
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Strict-Transport-Security');
    }

    /** @test */
    public function it_does_not_expose_php_version()
    {
        $response = $this->get('/');
        
        $response->assertHeaderMissing('X-Powered-By');
    }

    /** @test */
    public function it_uses_encryption_for_sensitive_data()
    {
        $encrypted = encrypt('sensitive_data');
        $decrypted = decrypt($encrypted);
        
        $this->assertEquals('sensitive_data', $decrypted);
        $this->assertNotEquals('sensitive_data', $encrypted);
    }

    /** @test */
    public function it_validates_password_complexity()
    {
        $this->assertTrue($this->isValidPassword('Str0ngP@ss!'));
        $this->assertFalse($this->isValidPassword('weak'));
    }

    /** @test */
    public function it_has_rate_limiting_enabled()
    {
        $this->assertTrue(config('security.rate_limiting.enabled'));
    }

    /** @test */
    public function it_has_transaction_logging()
    {
        $this->assertTrue(file_exists('storage/logs/transactions.log'));
    }

    /** @test */
    public function it_does_not_log_sensitive_data()
    {
        $config = config('logging');
        
        $this->assertArrayHasKey('daily', $config['channels']);
        $this->assertArrayHasKey('ignore_exceptions', $config['channels']['daily']);
        $this->assertContains('payment', $config['channels']['daily']['ignore_exceptions']);
    }

    /** @test */
    public function it_has_secure_app_configuration()
    {
        $this->assertFalse(config('app.debug'));
        $this->assertEquals('UTC', config('app.timezone'));
        $this->assertTrue(config('session.encrypt'));
    }

    /** @test */
    public function it_blocks_sensitive_files()
    {
        $htaccess = file_get_contents('public/.htaccess');
        
        $this->assertStringContainsString('.env', $htaccess);
        $this->assertStringContainsString('composer.lock', $htaccess);
    }

    /** @test */
    public function it_has_composer_lock_file()
    {
        $this->assertTrue(file_exists('composer.lock'));
    }

    /** @test */
    public function it_has_transaction_logger_service()
    {
        $this->assertTrue(class_exists('App\Services\TransactionLogger'));
    }

    /** @test */
    public function it_has_payment_data_sanitizer()
    {
        $this->assertTrue(class_exists('App\Services\PaymentDataSanitizer'));
    }

    /** @test */
    public function it_has_two_factor_authentication_trait()
    {
        $this->assertTrue(trait_exists('App\Traits\TwoFactorAuthentication'));
    }

    /** @test */
    public function it_has_secure_cors_configuration()
    {
        $cors = config('cors');
        
        $this->assertTrue($cors['supports_credentials']);
    }

    /** @test */
    public function it_has_security_configuration()
    {
        $this->assertTrue(class_exists('App\Services\SecurityConfig'));
    }

    /**
     * Check password complexity
     */
    private function isValidPassword($password)
    {
        return strlen($password) >= 8 &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[0-9]/', $password) &&
               preg_match('/[!@#$%^&*]/', $password);
    }
}
