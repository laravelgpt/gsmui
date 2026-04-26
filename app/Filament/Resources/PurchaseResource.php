<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Models\Purchase;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Sales';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only form for viewing
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Transaction ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('purchasable_type')
                    ->label('Item Type')
                    ->colors([
                        'info' => 'App\\Models\\Component',
                        'warning' => 'App\\Models\\Template',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'App\\Models\\Component' ? 'Component' : 'Template')
                    ->sortable(),

                TextColumn::make('purchasable.name')
                    ->label('Item Name')
                    ->getStateUsing(fn ($record) => $record->purchasable->name ?? 'N/A'),

                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),

                BadgeColumn::make('payment_status')
                    ->colors([
                        'success' => 'completed',
                        'danger' => 'failed',
                        'warning' => 'pending',
                        'info' => 'refunded',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'completed',
                        'heroicon-o-x-circle' => 'failed',
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-arrow-ut-left' => 'refunded',
                    ])
                    ->sortable(),

                TextColumn::make('transaction_id')
                    ->label('Payment ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('purchasable_type')
                    ->label('Item Type')
                    ->options([
                        'App\\Models\\Component' => 'Component',
                        'App\\Models\\Template' => 'Template',
                    ]),
                Tables\Filters\Filter::make('today')
                    ->query(fn (Builder $query) => $query->whereDate('created_at', today())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchases::route('/'),
        ];
    }
}
