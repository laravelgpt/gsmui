<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['name' => 'SaaS Landing Page', 'category' => 'landing', 'type' => 'free', 'is_active' => true, 'price' => 0],
            ['name' => 'E-commerce Landing', 'category' => 'landing', 'type' => 'paid', 'is_active' => true, 'price' => 49],
            ['name' => 'Portfolio Landing', 'category' => 'landing', 'type' => 'paid', 'is_active' => true, 'price' => 29],
            ['name' => 'Blog Landing Page', 'category' => 'landing', 'type' => 'free', 'is_active' => true, 'price' => 0],
            ['name' => 'Agency Landing', 'category' => 'landing', 'type' => 'paid', 'is_active' => true, 'price' => 59],
            ['name' => 'Startup Landing', 'category' => 'landing', 'type' => 'paid', 'is_active' => true, 'price' => 39],
            ['name' => 'App Landing Page', 'category' => 'landing', 'type' => 'paid', 'is_active' => true, 'price' => 49],
            ['name' => 'Mobile App Landing', 'category' => 'landing', 'type' => 'paid', 'is_active' => true, 'price' => 39],
            ['name' => 'Admin Dashboard', 'category' => 'dashboard', 'type' => 'paid', 'is_active' => true, 'price' => 79],
            ['name' => 'Analytics Dashboard', 'category' => 'dashboard', 'type' => 'paid', 'is_active' => true, 'price' => 69],
            ['name' => 'CRM Dashboard', 'category' => 'dashboard', 'type' => 'paid', 'is_active' => true, 'price' => 89],
            ['name' => 'Finance Dashboard', 'category' => 'dashboard', 'type' => 'paid', 'is_active' => true, 'price' => 79],
            ['name' => 'E-commerce Product', 'category' => 'ecommerce', 'type' => 'paid', 'is_active' => true, 'price' => 39],
            ['name' => 'Shop Listing Page', 'category' => 'ecommerce', 'type' => 'paid', 'is_active' => true, 'price' => 49],
            ['name' => 'Cart Page', 'category' => 'ecommerce', 'type' => 'paid', 'is_active' => true, 'price' => 29],
            ['name' => 'Checkout Flow', 'category' => 'ecommerce', 'type' => 'paid', 'is_active' => true, 'price' => 59],
            ['name' => 'Category Page', 'category' => 'ecommerce', 'type' => 'paid', 'is_active' => true, 'price' => 39],
            ['name' => 'Brand Showcase', 'category' => 'ecommerce', 'type' => 'paid', 'is_active' => true, 'price' => 49],
            ['name' => 'Login Page', 'category' => 'auth', 'type' => 'free', 'is_active' => true, 'price' => 0],
            ['name' => 'Register Page', 'category' => 'auth', 'type' => 'free', 'is_active' => true, 'price' => 0],
            ['name' => 'Onboarding Flow', 'category' => 'auth', 'type' => 'paid', 'is_active' => true, 'price' => 49],
            ['name' => '2FA Verification', 'category' => 'auth', 'type' => 'paid', 'is_active' => true, 'price' => 29],
            ['name' => 'Contact Form', 'category' => 'form', 'type' => 'free', 'is_active' => true, 'price' => 0],
            ['name' => 'Survey Form', 'category' => 'form', 'type' => 'paid', 'is_active' => true, 'price' => 39],
            ['name' => 'Application Form', 'category' => 'form', 'type' => 'paid', 'is_active' => true, 'price' => 59],
            ['name' => 'Booking Form', 'category' => 'form', 'type' => 'paid', 'is_active' => true, 'price' => 49],
            ['name' => 'Blog List Page', 'category' => 'blog', 'type' => 'free', 'is_active' => true, 'price' => 0],
            ['name' => 'Blog Post Page', 'category' => 'blog', 'type' => 'free', 'is_active' => true, 'price' => 0],
            ['name' => 'Author Profile', 'category' => 'blog', 'type' => 'paid', 'is_active' => true, 'price' => 29],
            ['name' => 'Comment Section', 'category' => 'blog', 'type' => 'paid', 'is_active' => true, 'price' => 39],
            ['name' => 'Feed Page', 'category' => 'social', 'type' => 'paid', 'is_active' => true, 'price' => 59],
            ['name' => 'Profile Page', 'category' => 'social', 'type' => 'paid', 'is_active' => true, 'price' => 49],
            ['name' => 'Group Page', 'category' => 'social', 'type' => 'paid', 'is_active' => true, 'price' => 49],
            ['name' => 'File Manager', 'category' => 'file', 'type' => 'paid', 'is_active' => true, 'price' => 69],
            ['name' => 'Uploader Page', 'category' => 'file', 'type' => 'paid', 'is_active' => true, 'price' => 59],
        ];

        foreach ($templates as $tmpl) {
            Template::updateOrCreate(['name' => $tmpl['name']], $tmpl);
        }
    }
}
