<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPurchases extends BaseWidget
{
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label('Transaction ID')
                ->searchable(),
            TextColumn::make('user.name')
                ->label('Customer')
                ->searchable(),
            TextColumn::make('amount')
                ->money('USD')
                ->sortable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ];
    }

    protected function getTableQuery()
    {
        return Purchase::query()
            ->where('payment_status', 'completed')
            ->with('user')
            ->latest();
    }

    protected function getTableHeading(): string
    {
        return 'Recent Purchases';
    }
}
