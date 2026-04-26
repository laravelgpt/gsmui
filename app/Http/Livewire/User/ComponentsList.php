<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\Component;
use App\Services\ComponentAccessService;

class ComponentsList extends Component
{
    public $search = '';
    public $category = '';
    public $type = '';

    protected $listeners = ['refreshList'];

    public function render(ComponentAccessService $accessService)
    {
        $user = auth()->user();

        $query = Component::where('is_active', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        $components = $query->orderBy('created_at', 'desc')->paginate(12);

        $components->getCollection()->transform(function ($component) use ($accessService, $user) {
            $component->accessible = $accessService->canAccessComponent($user, $component);
            $component->purchased = $user->purchases()
                ->where('purchasable_type', 'App\\Models\\Component')
                ->where('purchasable_id', $component->id)
                ->where('payment_status', 'completed')
                ->exists();
            return $component;
        });

        return view('livewire.user.components-list', [
            'components' => $components
        ]);
    }

    public function refreshList()
    {
        $this->render();
    }
}
