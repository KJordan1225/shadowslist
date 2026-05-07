<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Plumbing', 'icon' => '🚰'],
            ['name' => 'Electrical', 'icon' => '💡'],
            ['name' => 'HVAC', 'icon' => '❄️'],
            ['name' => 'Roofing', 'icon' => '🏠'],
            ['name' => 'Landscaping', 'icon' => '🌳'],
            ['name' => 'House Cleaning', 'icon' => '🧹'],
            ['name' => 'Painting', 'icon' => '🎨'],
            ['name' => 'Flooring', 'icon' => '🪵'],
            ['name' => 'General Contracting', 'icon' => '🛠️'],
            ['name' => 'Pest Control', 'icon' => '🐜'],
            ['name' => 'Appliance Repair', 'icon' => '🔧'],
            ['name' => 'Moving Services', 'icon' => '📦'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'is_active' => true,
                ]
            );
        }
    }
}
