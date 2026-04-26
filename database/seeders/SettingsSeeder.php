<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'theme_primary_color',
                'type' => 'color',
                'value' => '#00D4FF',
                'label' => 'Primary Color (Electric Blue)',
                'group' => 'theme',
                'description' => 'Main accent color for the Midnight Electric theme'
            ],
            [
                'key' => 'theme_secondary_color',
                'type' => 'color',
                'value' => '#39FF14',
                'label' => 'Secondary Color (Toxic Green)',
                'group' => 'theme',
                'description' => 'Secondary accent color for highlights and success states'
            ],
            [
                'key' => 'theme_accent_color',
                'type' => 'color',
                'value' => '#6366F1',
                'label' => 'Accent Color (Indigo)',
                'group' => 'theme',
                'description' => 'Tertiary accent color for gradients and subtle highlights'
            ],
            [
                'key' => 'theme_background_primary',
                'type' => 'color',
                'value' => '#0B0F19',
                'label' => 'Background Primary',
                'group' => 'theme',
                'description' => 'Main background color (deep space)'
            ],
            [
                'key' => 'theme_background_card',
                'type' => 'color',
                'value' => 'rgba(19, 24, 40, 0.9)',
                'label' => 'Card Background',
                'group' => 'theme',
                'description' => 'Glassmorphism card background with blur'
            ],
            [
                'key' => 'theme_glow_intensity',
                'type' => 'string',
                'value' => 'medium',
                'label' => 'Glow Intensity',
                'group' => 'theme',
                'description' => 'Controls the intensity of neon glow effects (low, medium, high)'
            ],
            [
                'key' => 'theme_border_radius',
                'type' => 'integer',
                'value' => '16',
                'label' => 'Border Radius',
                'group' => 'theme',
                'description' => 'Default border radius for cards and components (in pixels)'
            ],
            [
                'key' => 'theme_glass_blur',
                'type' => 'integer',
                'value' => '20',
                'label' => 'Glass Blur Amount',
                'group' => 'theme',
                'description' => 'Backdrop blur intensity for glassmorphism (in pixels)'
            ],
            [
                'key' => 'theme_grid_pattern',
                'type' => 'boolean',
                'value' => 'true',
                'label' => 'Enable Grid Pattern',
                'group' => 'theme',
                'description' => 'Show subtle grid pattern overlay'
            ],
            [
                'key' => 'theme_mesh_animation',
                'type' => 'boolean',
                'value' => 'true',
                'label' => 'Enable Mesh Animation',
                'group' => 'theme',
                'description' => 'Animate the background mesh gradient'
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
