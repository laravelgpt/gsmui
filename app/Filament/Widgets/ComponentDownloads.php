
<?php

namespace App\Filament\Widgets;

use App\Models\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class ComponentDownloads extends BaseWidget
{
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable()
                ->sortable(),
            BadgeColumn::make('type')
                ->colors([
                    'success' => 'free',
                    'primary' => 'premium',
                ])
                ->sortable(),
            TextColumn::make('download_count')
                ->label('Downloads')
                ->numeric()
                ->sortable(),
            TextColumn::make('category')
                ->sortable(),
        ];
    }

    protected function getTableQuery()
    {
        return Component::query()
            ->where('is_active', true)
            ->orderBy('download_count', 'desc')
            ->limit(5);
    }

    protected function getTableHeading(): string
    {
        return 'Top Components by Downloads';
    }
}
