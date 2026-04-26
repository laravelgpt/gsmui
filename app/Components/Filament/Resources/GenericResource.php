
<?php

namespace App\Components\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;

abstract class GenericResource extends Resource
{
    /** @var string */
    protected static ?string $model;
    
    /** @var string */
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    /** @var array */
    protected static array $columnSchema = [];
    
    /** @var array */
    protected static array $formSchema = [];

    public static function form(Form $form): Form
    {
        return $form->schema(static::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters(static::getTableFilters())
            ->actions(static::getTableActions())
            ->bulkActions(static::getBulkActions());
    }

    /**
     * Get form schema
     */
    protected static function getFormSchema(): array
    {
        if (!empty(static::$formSchema)) {
            return static::buildFormFromSchema(static::$formSchema);
        }
        
        return static::getDefaultFormSchema();
    }

    /**
     * Get table columns
     */
    protected static function getTableColumns(): array
    {
        if (!empty(static::$columnSchema)) {
            return static::buildColumnsFromSchema(static::$columnSchema);
        }
        
        return static::getDefaultTableColumns();
    }

    /**
     * Build form from schema
     */
    protected static function buildFormFromSchema(array $schema): array
    {
        return collect($schema)->map(function ($field) {
            $component = match($field['type']) {
                'text' => TextInput::make($field['name']),
                'textarea' => Textarea::make($field['name']),
                'select' => Select::make($field['name'])->options($field['options'] ?? []),
                'toggle' => Toggle::make($field['name']),
                default => TextInput::make($field['name']),
            };
            
            if (isset($field['label'])) {
                $component->label($field['label']);
            }
            
            if (isset($field['required']) && $field['required']) {
                $component->required();
            }
            
            return $component;
        })->toArray();
    }

    /**
     * Build columns from schema
     */
    protected static function buildColumnsFromSchema(array $schema): array
    {
        return collect($schema)->map(function ($column) {
            $type = $column['type'] ?? 'text';
            
            return match($type) {
                'text' => TextColumn::make($column['field'])->label($column['label'] ?? ''),
                'badge' => BadgeColumn::make($column['field'])->label($column['label'] ?? ''),
                'image' => ImageColumn::make($column['field'])->label($column['label'] ?? ''),
                default => TextColumn::make($column['field'])->label($column['label'] ?? ''),
            };
        })->toArray();
    }

    /**
     * Get default form schema
     */
    protected static function getDefaultFormSchema(): array
    {
        $model = static::getModel();
        $fillable = (new $model)->getFillable();
        
        return collect($fillable)->map(function ($field) {
            return TextInput::make($field)->label(ucfirst($field));
        })->toArray();
    }

    /**
     * Get default table columns
     */
    protected static function getDefaultTableColumns(): array
    {
        $model = static::getModel();
        $fillable = (new $model)->getFillable();
        
        return collect($fillable)->map(function ($field) {
            return TextColumn::make($field)->label(ucfirst($field));
        })->toArray();
    }

    protected static function getTableFilters(): array
    {
        return [];
    }

    protected static function getTableActions(): array
    {
        return [];
    }

    protected static function getBulkActions(): array
    {
        return [];
    }
}
