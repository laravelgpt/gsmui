
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Component;
use App\Models\Template;
use App\Services\ComponentAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComponentAccessTest extends TestCase
{
    use RefreshDatabase;

    protected ComponentAccessService $accessService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accessService = new ComponentAccessService();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_allows_access_to_free_components()
    {
        $component = Component::factory()->create([
            'type' => 'free',
        ]);

        $result = $this->accessService->canAccessComponent($this->user, $component);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_access_to_premium_components_without_subscription()
    {
        $component = Component::factory()->create([
            'type' => 'premium',
        ]);

        $result = $this->accessService->canAccessComponent($this->user, $component);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_allows_access_to_premium_components_with_subscription()
    {
        $this->user->update(['has_active_subscription' => true]);
        
        $component = Component::factory()->create([
            'type' => 'premium',
        ]);

        $result = $this->accessService->canAccessComponent($this->user, $component);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_allows_access_to_premium_components_with_purchase()
    {
        $component = Component::factory()->create([
            'type' => 'premium',
        ]);

        $component->purchases()->create([
            'user_id' => $this->user->id,
            'payment_status' => 'completed',
            'amount' => 49.99,
            'currency' => 'USD',
        ]);

        $result = $this->accessService->canAccessComponent($this->user, $component);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_returns_component_code_when_accessible()
    {
        $component = Component::factory()->create([
            'type' => 'free',
            'code_snippet' => '<div>Test Component</div>',
        ]);

        $code = $this->accessService->getComponentCode($this->user, $component);

        $this->assertEquals('<div>Test Component</div>', $code);
    }

    /** @test */
    public function it_returns_null_code_when_not_accessible()
    {
        $component = Component::factory()->create([
            'type' => 'premium',
            'code_snippet' => '<div>Premium Component</div>',
        ]);

        $code = $this->accessService->getComponentCode($this->user, $component);

        $this->assertNull($code);
    }

    /** @test */
    public function it_handles_download_for_inaccessible_component()
    {
        $component = Component::factory()->create([
            'type' => 'premium',
            'slug' => 'premium-button',
            'category' => 'forms',
            'code_snippet' => '<button>Premium</button>',
        ]);

        $result = $this->accessService->downloadComponentForCLI($this->user, $component);

        $this->assertFalse($result['success']);
        $this->assertEquals(403, $result['code']);
        $this->assertStringContainsString('Access denied', $result['error']);
    }

    /** @test */
    public function it_handles_download_for_accessible_component()
    {
        $component = Component::factory()->create([
            'type' => 'free',
            'slug' => 'free-button',
            'category' => 'utilities',
            'code_snippet' => '<button class="gsm-button">Free Button</button>',
        ]);

        $result = $this->accessService->downloadComponentForCLI($this->user, $component);

        $this->assertTrue($result['success']);
        $this->assertEquals('free-button.blade.php', $result['filename']);
        $this->assertEquals('utilities', $result['category']);
        $this->assertStringContainsString('Free Button', $result['code']);
    }

    /** @test */
    public function it_returns_accessible_component_ids()
    {
        $freeComponent = Component::factory()->create(['type' => 'free']);
        $premiumComponent = Component::factory()->create(['type' => 'premium']);
        $purchasedComponent = Component::factory()->create(['type' => 'premium']);

        $purchasedComponent->purchases()->create([
            'user_id' => $this->user->id,
            'payment_status' => 'completed',
            'amount' => 49.99,
            'currency' => 'USD',
        ]);

        $ids = $this->accessService->getAccessibleComponentIds($this->user);

        $this->assertContains($freeComponent->id, $ids);
        $this->assertNotContains($premiumComponent->id, $ids);
        $this->assertContains($purchasedComponent->id, $ids);
    }

    /** @test */
    public function it_allows_access_to_free_template()
    {
        $template = Template::factory()->create(['type' => 'free']);

        $result = $this->accessService->canAccessTemplate($this->user, $template);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_allows_template_access_with_subscription()
    {
        $this->user->update(['has_active_subscription' => true]);
        $template = Template::factory()->create(['type' => 'premium']);

        $result = $this->accessService->canAccessTemplate($this->user, $template);

        $this->assertTrue($result);
    }
}
