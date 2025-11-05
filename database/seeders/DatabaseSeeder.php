<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AuthToken;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create user (id 1)
        $user = User::firstOrCreate([
            'email' => 'user@example.com'
        ], [
            'name' => 'Demo User',
            'password' => 'password'
        ]);

        // Create admin (id 2)
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Demo Admin',
            'password' => 'password'
        ]);

        // Create API tokens (fixed for demo)
        AuthToken::firstOrCreate(['token' => 'user-token-1'], ['user_id' => $user->id, 'is_admin' => false]);
        AuthToken::firstOrCreate(['token' => 'admin-token-2'], ['user_id' => $admin->id, 'is_admin' => true]);

        // Sample products
        $products = [
            ['title' => 'Sony WH-1000XM5', 'slug' => 'sony-wh-1000xm5', 'price' => 8990000, 'quantity' => 25, 'thumb' => '/img/hero1.webp'],
            ['title' => 'Logitech MX Keys', 'slug' => 'logitech-mx-keys', 'price' => 2290000, 'quantity' => 15, 'thumb' => '/img/hero2.png'],
            ['title' => 'Apple AirPods Pro', 'slug' => 'apple-airpods-pro', 'price' => 4990000, 'quantity' => 30, 'thumb' => '/img/hero3.png'],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(['slug' => $p['slug']], array_merge($p, ['status' => 1]));
        }
    }
}
