<?php

namespace App\Components\Shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DataTableComponent extends BaseComponent
{
    /** @var Builder */
    protected Builder $query;

    /** @var array */
    protected array $columns;

    /** @var array */
    protected array $filters = [];

    /** @var array */
    protected array $sortable = [];

    /** @var array */
    protected array $actions = [];

    /**
     * Constructor
     */
    public function __construct(
        string $name,
        Builder $query,
        array $columns,
        array $props = []
    ) {
        $this->name = $name;
        $this->query = $query;
        $this->columns = $columns;
        parent::__construct($props);
    }

    /**
     * Render component data
     */
    public function renderData(): array
    {
        $query = clone $this->query;

        // Apply filters
        foreach ($this->filters as $filter) {
            $query = $this->applyFilter($query, $filter);
        }

        // Apply sorting
        if (request()->has('sort') && in_array(request('sort'), $this->sortable)) {
            $direction = request('direction', 'asc');
            $query->orderBy(request('sort'), $direction);
        }

        // Paginate
        $perPage = $this->props['perPage'] ?? 10;
        $items = $query->paginate($perPage);

        return [
            'columns' => $this->columns,
            'items' => $items,
            'filters' => $this->filters,
            'sortable' => $this->sortable,
            'actions' => $this->actions,
        ];
    }

    /**
     * Get column definition
     */
    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Add filter
     */
    public function addFilter(string $field, string $type = 'text', array $options = []): self
    {
        $this->filters[$field] = compact('type', 'options');
        return $this;
    }

    /**
     * Add sortable column
     */
    public function sortable(string ...$columns): self
    {
        $this->sortable = array_merge($this->sortable, $columns);
        return $this;
    }

    /**
     * Add action
     */
    public function addAction(string $name, string $type, string $handler, array $options = []): self
    {
        $this->actions[] = compact('name', 'type', 'handler', 'options');
        return $this;
    }

    /**
     * Apply filter to query
     */
    protected function applyFilter(Builder $query, array $filter): Builder
    {
        $field = key($filter);
        $value = request($field);

        if (!$value) {
            return $query;
        }

        $type = $filter['type'] ?? 'text';

        return match($type) {
            'exact' => $query->where($field, $value),
            'like' => $query->where($field, 'like', "%{$value}%"),
            'date' => $query->whereDate($field, $value),
            'range' => $query->whereBetween($field, [$value['from'], $value['to']]),
            'in' => $query->whereIn($field, (array) $value),
            default => $query->where($field, 'like', "%{$value}%"),
        };
    }

    /**
     * Get config
     */
    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'stack' => 'universal',
            'theme' => 'default',
            'variant' => 'datatable',
            'responsive' => true,
            'interactive' => true,
            'exportable' => $this->props['exportable'] ?? true,
            'searchable' => $this->props['searchable'] ?? true,
        ]);
    }

    /**
     * Get default props
     */
    public function getDefaultProps(): array
    {
        return [
            'perPage' => 10,
            'exportable' => true,
            'searchable' => true,
            'striped' => true,
            'hoverable' => true,
            'bordered' => false,
        ];
    }

    /**
     * Validate props
     */
    public function validateProps(array $props): bool
    {
        return !empty($this->columns);
    }

    /**
     * Get actions
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * Execute action
     */
    public function executeAction(string $action, array $params = []): mixed
    {
        return match($action) {
            'export' => $this->export($params['format'] ?? 'csv'),
            'refresh' => $this->refresh(),
            'bulkDelete' => $this->bulkDelete($params['ids'] ?? []),
            default => throw new \Exception("Action {$action} not supported"),
        };
    }

    /**
     * Check if action is allowed
     */
    public function canExecute(string $action): bool
    {
        return in_array($action, ['export', 'refresh', 'bulkDelete']);
    }

    /**
     * Export data
     */
    protected function export(string $format): mixed
    {
        // Implementation depends on format
        return ['status' => 'exporting', 'format' => $format];
    }

    /**
     * Refresh table
     */
    protected function refresh(): mixed
    {
        return $this->renderData();
    }

    /**
     * Bulk delete
     */
    protected function bulkDelete(array $ids): mixed
    {
        return $this->query->whereIn('id', $ids)->delete();
    }
}
