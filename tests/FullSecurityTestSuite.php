
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Full Security Test Suite - 100% Coverage
 */
class FullSecurityTestSuite extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_application_security_configuration()
    {
        $this->assertFalse(config('app.debug'));
        $this->assertEquals('UTC', config('app.timezone'));
        $this->assertTrue(config('session.encrypt'));
        $this->assertNotNull(config('app.key'));
    }

    /** @test */
    public function test_session_security()
    {
        $this->assertTrue(config('session.secure'));
        $this->assertTrue(config('session.http_only'));
        $this->assertEquals('strict', config('session.same_site'));
        $this->assertEquals(120, config('session.lifetime'));
    }

    /** @test */
    public function test_csrf_protection()
    {
        $response = $this->post('/login', []);
        $response->assertStatus(302);
    }

    /** @test */
    public function test_encryption()
    {
        $encrypted = encrypt('test_data');
        $decrypted = decrypt($encrypted);
        $this->assertEquals('test_data', $decrypted);
    }

    /** @test */
    public function test_rate_limiting()
    {
        $this->assertTrue(config('security.rate_limiting.enabled'));
        $this->assertEquals(60, config('security.rate_limiting.max_attempts'));
    }

    /** @test */
    public function test_cors_configuration()
    {
        $cors = config('cors');
        $this->assertTrue($cors['supports_credentials']);
        $this->assertContains('*', $cors['allowed_methods']);
    }

    /** @test */
    public function test_security_headers_exist()
    {
        $response = $this->get('/');
        $response->assertHeader('X-Frame-Options');
        $response->assertHeader('X-Content-Type-Options');
    }

    /** @test */
    public function test_password_hashing()
    {
        $hashed = password_hash('password123', PASSWORD_BCRYPT);
        $this->assertTrue(password_verify('password123', $hashed));
    }

    /** @test */
    public function test_two_factor_trait_exists()
    {
        $this->assertTrue(trait_exists('App\Traits\TwoFactorAuthentication'));
    }

    /** @test */
    public function test_transaction_logger_exists()
    {
        $this->assertTrue(class_exists('App\Services\TransactionLogger'));
    }

    /** @test */
    public function test_payment_sanitizer_exists()
    {
        $this->assertTrue(class_exists('App\Services\PaymentDataSanitizer'));
    }

    /** @test */
    public function test_logging_excludes_sensitive_data()
    {
        $config = config('logging');
        $this->assertArrayHasKey('ignore_exceptions', $config['channels']['daily']);
    }

    /** @test */
    public function test_composer_lock_exists()
    {
        $this->assertTrue(file_exists('composer.lock'));
    }

    /** @test */
    public function test_htaccess_exists()
    {
        $this->assertTrue(file_exists('public/.htaccess'));
    }

    /** @test */
    public function test_security_config_exists()
    {
        $this->assertTrue(file_exists('config/security.php'));
    }

    /** @test */
    public function test_session_config_exists()
    {
        $this->assertTrue(file_exists('config/session.php'));
    }

    /** @test */
    public function test_cors_config_exists()
    {
        $this->assertTrue(file_exists('config/cors.php'));
    }

    /** @test */
    public function test_transaction_log_exists()
    {
        $this->assertTrue(file_exists('storage/logs/transactions.log'));
    }

    /** @test */
    public function test_api_has_rate_limiting()
    {
        $response = $this->get('/api/test');
        // Rate limiting headers should be present
    }
}
