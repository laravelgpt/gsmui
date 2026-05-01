<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ComponentManager extends Component
{
    use WithFileUploads, WithPagination;

    public $showModal = false;
    public $showDeleteModal = false;
    public $showBulkActions = false;
    public $selectedComponent = null;
    public $search = '';
    public $category = '';
    public $type = '';
    public $selectedItems = [];
    public $selectAll = false;

    // Form fields
    public $componentId;
    public $name;
    public $description;
    public $componentCategory;
    public $componentType;
    public $price;
    public $is_active;
    public $preview_url;
    public $file;
    public $tags = [];
    public $compatibility = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|min:10',
        'componentCategory' => 'required|string|max:100',
        'componentType' => 'required|in:free,paid,premium',
        'price' => 'nullable|numeric|min:0',
        'preview_url' => 'nullable|url',
        'file' => 'nullable|file|max:10240',
    ];

    protected $listeners = ['refreshComponentList' => '$refresh'];

    public function mount()
    {
        $this->is_active = true;
        $this->componentType = 'free';
    }

    public function render()
    {
        $components = Component::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('description', 'like', '%'.$this->search.'%'))
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = Component::select('category')->distinct()->pluck('category');
        $types = Component::select('type')->distinct()->pluck('type');

        return view('livewire.admin.component-manager', compact('components', 'categories', 'types'))
            ->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->selectedComponent = null;
    }

    public function edit($id)
    {
        $this->selectedComponent = Component::findOrFail($id);
        $this->componentId = $this->selectedComponent->id;
        $this->name = $this->selectedComponent->name;
        $this->description = $this->selectedComponent->description;
        $this->componentCategory = $this->selectedComponent->category;
        $this->componentType = $this->selectedComponent->type;
        $this->price = $this->selectedComponent->price;
        $this->is_active = $this->selectedComponent->is_active;
        $this->preview_url = $this->selectedComponent->preview_url;
        $this->tags = $this->selectedComponent->tags ?? [];
        $this->compatibility = $this->selectedComponent->compatibility ?? [];

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->componentCategory,
            'type' => $this->componentType,
            'price' => $this->componentType === 'free' ? 0 : $this->price,
            'is_active' => $this->is_active,
            'preview_url' => $this->preview_url,
            'tags' => $this->tags,
            'compatibility' => $this->compatibility,
        ];

        if ($this->file) {
            $path = $this->file->store('components/' . strtolower($this->name), 'public');
            $data['file_path'] = $path;
        }

        if ($this->componentId) {
            $component = Component::findOrFail($this->componentId);
            $component->update($data);
            $this->emit('success', 'Component updated successfully!');
        } else {
            Component::create($data);
            $this->emit('success', 'Component created successfully!');
        }

        $this->closeModal();
        $this->dispatchBrowserEvent('refresh-table');
    }

    public function delete($id)
    {
        $this->selectedComponent = Component::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if ($this->selectedComponent) {
            // Clean up files
            if ($this->selectedComponent->file_path) {
                Storage::disk('public')->delete($this->selectedComponent->file_path);
            }
            $this->selectedComponent->delete();
            $this->emit('success', 'Component deleted successfully!');
        }
        $this->showDeleteModal = false;
        $this->selectedComponent = null;
        $this->dispatchBrowserEvent('refresh-table');
    }

    public function toggleBulkActions()
    {
        $this->showBulkActions = !$this->showBulkActions;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = Component::pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    public function bulkDelete()
    {
        if (empty($this->selectedItems)) {
            $this->emit('error', 'Please select items to delete');
            return;
        }

        Component::whereIn('id', $this->selectedItems)->delete();
        $this->emit('success', count($this->selectedItems) . ' components deleted');
        $this->selectedItems = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
        $this->dispatchBrowserEvent('refresh-table');
    }

    public function bulkActivate()
    {
        Component::whereIn('id', $this->selectedItems)->update(['is_active' => true]);
        $this->emit('success', count($this->selectedItems) . ' components activated');
        $this->resetBulkSelection();
    }

    public function bulkDeactivate()
    {
        Component::whereIn('id', $this->selectedItems)->update(['is_active' => false]);
        $this->emit('success', count($this->selectedItems) . ' components deactivated');
        $this->resetBulkSelection();
    }

    private function resetBulkSelection()
    {
        $this->selectedItems = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
        $this->dispatchBrowserEvent('refresh-table');
    }

    private function resetForm()
    {
        $this->reset(['componentId', 'name', 'description', 'componentCategory', 'componentType', 'price', 'is_active', 'preview_url', 'file', 'tags', 'compatibility']);
        $this->is_active = true;
        $this->componentType = 'free';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
        $this->resetValidation();
    }
}
ENDOFFILE
echo "ComponentManager Livewire created"
