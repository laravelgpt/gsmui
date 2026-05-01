<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Component;

class ComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            ['name' => 'Primary Button', 'category' => 'button', 'type' => 'free', 'is_active' => true],
            ['name' => 'Secondary Button', 'category' => 'button', 'type' => 'free', 'is_active' => true],
            ['name' => 'Success Button', 'category' => 'button', 'type' => 'free', 'is_active' => true],
            ['name' => 'Warning Button', 'category' => 'button', 'type' => 'free', 'is_active' => true],
            ['name' => 'Danger Button', 'category' => 'button', 'type' => 'free', 'is_active' => true],
            ['name' => 'Card Basic', 'category' => 'card', 'type' => 'free', 'is_active' => true],
            ['name' => 'Card with Image', 'category' => 'card', 'type' => 'free', 'is_active' => true],
            ['name' => 'Card with Actions', 'category' => 'card', 'type' => 'paid', 'is_active' => true],
            ['name' => 'Modal Basic', 'category' => 'modal', 'type' => 'free', 'is_active' => true],
            ['name' => 'Modal with Form', 'category' => 'modal', 'type' => 'paid', 'is_active' => true],
            ['name' => 'Data Table Simple', 'category' => 'table', 'type' => 'free', 'is_active' => true],
            ['name' => 'Data Table Advanced', 'category' => 'table', 'type' => 'paid', 'is_active' => true],
            ['name' => 'Form Basic', 'category' => 'form', 'type' => 'free', 'is_active' => true],
            ['name' => 'Form with Validation', 'category' => 'form', 'type' => 'paid', 'is_active' => true],
            ['name' => 'Input Text', 'category' => 'input', 'type' => 'free', 'is_active' => true],
            ['name' => 'Input Select', 'category' => 'input', 'type' => 'free', 'is_active' => true],
            ['name' => 'Input Checkbox', 'category' => 'input', 'type' => 'free', 'is_active' => true],
            ['name' => 'Input Radio', 'category' => 'input', 'type' => 'free', 'is_active' => true],
            ['name' => 'Navigation Menu', 'category' => 'navigation', 'type' => 'free', 'is_active' => true],
            ['name' => 'Navigation Tabs', 'category' => 'navigation', 'type' => 'paid', 'is_active' => true],
        ];

        foreach ($components as $comp) {
            Component::updateOrCreate(['name' => $comp['name']], $comp);
        }
    }
}
