<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DashboardLayout extends Component
{
    public $activeTab = 'components';
    public $userComponents = [];
    public $userTemplates = [];

    protected $listeners = ['tabChanged'];

    public function mount()
    {
        $user = Auth::user();
        $this->userComponents = $user->purchases()
            ->where('purchasable_type', 'App\\Models\\Component')
            ->where('payment_status', 'completed')
            ->with('purchasable')
            ->get();

        $this->userTemplates = $user->purchases()
            ->where('purchasable_type', 'App\\Models\\Template')
            ->where('payment_status', 'completed')
            ->with('purchasable')
            ->get();
    }

    public function tabChanged($tab)
    {
        $this->activeTab = $tab;
    }

    public function generateApiKey()
    {
        $user = Auth::user();
        $user->forceFill([
            'api_token' => \Illuminate\Support\Str::random(40)
        ])->save();

        $this->dispatch('apiKeyGenerated');
    }

    public function render()
    {
        return view('livewire.user.dashboard-layout');
    }
}
