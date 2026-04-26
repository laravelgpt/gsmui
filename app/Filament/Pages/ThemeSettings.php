<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;

class ThemeSettings extends Page
{
    protected static string $view = 'filament.pages.theme-settings';

    public $theme_primary_color;
    public $theme_secondary_color;
    public $theme_accent_color;
    public $theme_background_primary;
    public $theme_background_card;
    public $theme_glow_intensity;
    public $theme_border_radius;
    public $theme_glass_blur;
    public $theme_grid_pattern;
    public $theme_mesh_animation;

    protected function getFormSchema(): array
    {
        return [
            Section::make('Core Colors')
                ->schema([
                    ColorPicker::make('theme_primary_color')
                        ->label('Primary Color (Electric Blue)')
                        ->required(),
                    ColorPicker::make('theme_secondary_color')
                        ->label('Secondary Color (Toxic Green)')
                        ->required(),
                    ColorPicker::make('theme_accent_color')
                        ->label('Accent Color')
                        ->required(),
                ])
                ->columns(3),

            Section::make('Background Colors')
                ->schema([
                    ColorPicker::make('theme_background_primary')
                        ->label('Primary Background')
                        ->required(),
                    ColorPicker::make('theme_background_card')
                        ->label('Card Background')
                        ->required(),
                ])
                ->columns(2),

            Section::make('Effects')
                ->schema([
                    Select::make('theme_glow_intensity')
                        ->label('Glow Intensity')
                        ->options([
                            'low' => 'Low',
                            'medium' => 'Medium',
                            'high' => 'High',
                        ])
                        ->required(),
                    TextInput::make('theme_border_radius')
                        ->label('Border Radius (px)')
                        ->numeric()
                        ->required(),
                    TextInput::make('theme_glass_blur')
                        ->label('Glass Blur (px)')
                        ->numeric()
                        ->required(),
                ])
                ->columns(3),

            Section::make('Animations')
                ->schema([
                    Toggle::make('theme_grid_pattern')
                        ->label('Enable Grid Pattern')
                        ->required(),
                    Toggle::make('theme_mesh_animation')
                        ->label('Enable Mesh Animation')
                        ->required(),
                ])
                ->columns(2),
        ];
    }

    public function mount()
    {
        $settings = DB::table('settings')->get()->keyBy('key');

        $this->theme_primary_color = $settings->get('theme_primary_color')->value ?? '#00D4FF';
        $this->theme_secondary_color = $settings->get('theme_secondary_color')->value ?? '#39FF14';
        $this->theme_accent_color = $settings->get('theme_accent_color')->value ?? '#6366F1';
        $this->theme_background_primary = $settings->get('theme_background_primary')->value ?? '#0B0F19';
        $this->theme_background_card = $settings->get('theme_background_card')->value ?? 'rgba(19, 24, 40, 0.9)';
        $this->theme_glow_intensity = $settings->get('theme_glow_intensity')->value ?? 'medium';
        $this->theme_border_radius = $settings->get('theme_border_radius')->value ?? '16';
        $this->theme_glass_blur = $settings->get('theme_glass_blur')->value ?? '20';
        $this->theme_grid_pattern = $settings->get('theme_grid_pattern')->value ?? 'true';
        $this->theme_mesh_animation = $settings->get('theme_mesh_animation')->value ?? 'true';
    }

    public function save()
    {
        $data = $this->getFormState();

        foreach ($data as $key => $value) {
            DB::table('settings')
                ->where('key', $key)
                ->update(['value' => $value]);
        }

        $this->dispatch('notify', 'Theme settings saved successfully!');
    }
}
