<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'city' => 'Roanoke',
                'state' => 'VA',
                'zip' => '24011',
            ]
        );

        $provider = User::firstOrCreate(
            ['email' => 'provider@example.com'],
            [
                'name' => 'Provider User',
                'password' => Hash::make('password'),
                'role' => 'provider',
                'city' => 'Roanoke',
                'state' => 'VA',
                'zip' => '24011',
            ]
        );

        $homeowner = User::firstOrCreate(
            ['email' => 'homeowner@example.com'],
            [
                'name' => 'Homeowner User',
                'password' => Hash::make('password'),
                'role' => 'homeowner',
                'city' => 'Roanoke',
                'state' => 'VA',
                'zip' => '24011',
            ]
        );

        $business = Business::firstOrCreate(
            ['user_id' => $provider->id],
            [
                'name' => 'Star City Plumbing',
                'slug' => 'star-city-plumbing-' . Str::random(6),
                'description' => 'Reliable local plumbing services for repairs, installations, drain cleaning, water heaters, and emergency service.',
                'phone' => '540-555-1000',
                'email' => 'provider@example.com',
                'website' => 'https://example.com',
                'address' => '100 Market Street',
                'city' => 'Roanoke',
                'state' => 'VA',
                'zip' => '24011',
                'service_radius' => 35,
                'years_in_business' => 12,
                'licensed' => true,
                'insured' => true,
                'featured' => true,
                'status' => 'active',
            ]
        );

        $category = Category::where('slug', 'plumbing')->first();

        if ($category) {
            $business->categories()->syncWithoutDetaching([$category->id]);
        }

        $business->reviews()->firstOrCreate(
            [
                'user_id' => $homeowner->id,
            ],
            [
                'rating' => 5,
                'title' => 'Excellent service',
                'body' => 'They showed up on time, explained the repair clearly, and completed the work professionally.',
                'service_used' => 'Pipe repair',
                'would_hire_again' => true,
                'status' => 'approved',
            ]
        );

        $business->recalculateRating();
    }
}
