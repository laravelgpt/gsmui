<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComponentResource\Pages;
use App\Models\Component;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class ComponentResource extends Resource
{
    protected static ?string $model = Component::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Components';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Component Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', \Illuminate\Support\Str::slug($state));
                            }),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\MarkdownEditor::make('description')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('category')
                            ->required()
                            ->options([
                                'data-display' => 'Data Display',
                                'filters' => 'Filters',
                                'actions' => 'Actions',
                                'forms' => 'Forms',
                                'navigation' => 'Navigation',
                                'feedback' => 'Feedback',
                                'layout' => 'Layout',
                            ]),

                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'free' => 'Free',
                                'premium' => 'Premium',
                            ]),

                        Forms\Components\Textarea::make('code_snippet')
                            ->required()
                            ->rows(20)
                            ->columnSpanFull()
                            ->helperText('Enter the Blade component code'),

                        Forms\Components\Textarea::make('preview_html')
                            ->rows(10)
                            ->columnSpanFull()
                            ->helperText('HTML preview of the component'),

                        Forms\Components\KeyValue::make('metadata')
                            ->columnSpanFull()
                            ->helperText('Additional metadata (price, version, etc.)'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                BadgeColumn::make('type')
                    ->colors([
                        'success' => 'free',
                        'primary' => 'premium',
                    ])
                    ->icons([
                        'heroicon-o-lock-open' => 'free',
                        'heroicon-o-lock-closed' => 'premium',
                    ])
                    ->sortable(),

                BadgeColumn::make('category')
                    ->colors(['info'])
                    ->sortable(),

                TextColumn::make('download_count')
                    ->label('Downloads')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('is_active')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'free' => 'Free',
                        'premium' => 'Premium',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'data-display' => 'Data Display',
                        'filters' => 'Filters',
                        'actions' => 'Actions',
                        'forms' => 'Forms',
                        'navigation' => 'Navigation',
                        'feedback' => 'Feedback',
                        'layout' => 'Layout',
                    ]),
                Tables\Filters\Filter::make('is_active')
                    ->query(fn ($query) => $query->where('is_active', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComponents::route('/'),
            'create' => Pages\CreateComponent::route('/create'),
            'edit' => Pages\EditComponent::route('/{record}/edit'),
        ];
    }
}
