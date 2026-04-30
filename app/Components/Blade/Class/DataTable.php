<?php

namespace App\Components\Blade\Class;

use App\Components\Shared\DataTableComponent;
use Illuminate\View\Component;

class DataTable extends Component
{
    /** @var DataTableComponent */
    protected DataTableComponent $component;

    /**
     * Constructor
     */
    public function __construct(DataTableComponent $component)
    {
        $this->component = $component;
    }

    /**
     * Get data for view
     */
    public function getData(): array
    {
        return $this->component->renderData();
    }

    /**
     * Get columns
     */
    public function getColumns(): array
    {
        return $this->component->columns;
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('components.blade.datatable', $this->getData());
    }
}
