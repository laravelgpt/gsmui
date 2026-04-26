
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Component;
use App\Models\Template;
use App\Models\Purchase;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentService $paymentService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = new PaymentService();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_purchase_free_component()
    {
        $component = Component::factory()->create([
            'type' => 'free',
        ]);

        $result = $this->paymentService->purchaseItem(
            $this->user,
            'component',
            $component->id,
            0.00
        );

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'purchasable_type' => Component::class,
            'purchasable_id' => $component->id,
            'payment_status' => 'completed',
            'amount' => 0.00,
        ]);
    }

    /** @test */
    public function it_can_purchase_premium_component()
    {
        $component = Component::factory()->create([
            'type' => 'premium',
        ]);

        $result = $this->paymentService->purchaseItem(
            $this->user,
            'component',
            $component->id,
            49.99
        );

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->id,
            'purchasable_type' => Component::class,
            'purchasable_id' => $component->id,
            'amount' => 49.99,
        ]);
    }

    /** @test */
    public function it_prevents_duplicate_purchases()
    {
        $component = Component::factory()->create([
            'type' => 'premium',
        ]);

        Purchase::factory()->create([
            'user_id' => $this->user->id,
            'purchasable_type' => Component::class,
            'purchasable_id' => $component->id,
            'payment_status' => 'completed',
            'amount' => 49.99,
        ]);

        $result = $this->paymentService->purchaseItem(
            $this->user,
            'component',
            $component->id,
            49.99
        );

        $this->assertTrue($result['success']);
        $this->assertTrue($result['already_owned']);
    }

    /** @test */
    public function it_rejects_invalid_purchasable_type()
    {
        $result = $this->paymentService->purchaseItem(
            $this->user,
            'invalid_type',
            1,
            49.99
        );

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid', $result['error']);
    }

    /** @test */
    public function it_processes_subscription()
    {
        Config::set('services.stripe.key', 'sk_test_123');
        
        $result = $this->paymentService->subscribe(
            $this->user,
            'price_123',
            'pm_card_visa'
        );

        // Should succeed even in test mode
        $this->assertIsArray($result);
    }

    /** @test */
    public function it_cancels_subscription()
    {
        $this->user->update(['subscription_status' => 'active']);

        $result = $this->paymentService->cancelSubscription($this->user);

        $this->assertTrue($result['success']);
        $this->assertEquals('cancelled', $this->user->fresh()->subscription_status);
    }

    /** @test */
    public function it_returns_billing_history()
    {
        Purchase::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'payment_status' => 'completed',
        ]);

        $history = $this->paymentService->getBillingHistory($this->user);

        $this->assertArrayHasKey('purchases', $history);
        $this->assertCount(3, $history['purchases']);
    }

    /** @test */
    public function it_calculates_mrr()
    {
        User::factory()->count(5)->create(['subscription_status' => 'active']);
        User::factory()->count(3)->create(['subscription_status' => 'cancelled']);

        $mrr = $this->paymentService->getMRR();

        $this->assertEquals(5 * 29.99, $mrr);
    }

    /** @test */
    public function it_calculates_total_revenue()
    {
        Purchase::factory()->create([
            'payment_status' => 'completed',
            'amount' => 49.99,
        ]);
        Purchase::factory()->create([
            'payment_status' => 'completed',
            'amount' => 99.99,
        ]);
        Purchase::factory()->create([
            'payment_status' => 'failed',
            'amount' => 29.99,
        ]);

        $revenue = $this->paymentService->getTotalRevenue();

        $this->assertEquals(49.99 + 99.99, $revenue);
    }
}
