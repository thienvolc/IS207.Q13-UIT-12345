<?php

namespace Database\Seeders;

use App\Domains\Catalog\Entities\Product;
use App\Domains\Identity\Entities\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create user (id 1) - Token will be: user-1
        $user = User::firstOrCreate([
            'email' => 'user@example.com'
        ], [
            'name' => 'Demo User',
            'password' => 'password'
        ]);

        // Create admin (id 2) - Token will be: admin-2
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Demo Admin',
            'password' => 'password'
        ]);

        // Note: Tokens are NOT stored in database!
        // After login, tokens are generated as: "user-{user_id}" or "admin-{user_id}"
        // Example: "user-1" for user@example.com, "admin-2" for admin@example.com

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
