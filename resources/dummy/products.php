<?php
// resources/dummy/products.php

use Illuminate\Support\Str;

return collect(range(1, 30))->map(function ($i) {
    $name = match ($i) {
        1 => 'iPhone 16 Pro Max 256GB',
        2 => 'Samsung Galaxy S24 Ultra 512GB',
        3 => 'Xiaomi 14 Pro 12GB RAM',
        4 => 'MacBook Air M3 13" 2024',
        5 => 'iPad Pro 11" M2 256GB',
        6 => 'Sony WH-1000XM5 Black',
        7 => 'Apple Watch Ultra 2 GPS + Cellular',
        8 => 'AirPods Pro 2nd Gen',
        9 => 'Dell XPS 14 OLED 2024',
        10 => 'Asus ROG Strix G16 RTX 4070',
        11 => 'Oppo Find X7 Ultra',
        12 => 'Google Pixel 9 Pro XL',
        13 => 'Samsung Galaxy Z Fold 6',
        14 => 'OnePlus 12 16GB RAM',
        15 => 'Bose QuietComfort Ultra',
        default => "Sản phẩm điện tử #{$i}",
    };

    $price = fake()->numberBetween(12000000, 60000000);
    $price_sale = $price - fake()->numberBetween(1000000, 15000000);
    $discount = $price > $price_sale ? round((($price - $price_sale) / $price) * 100) : 0;

    return [
        'id' => $i,
        'name' => $name,
        'slug' => Str::slug($name),
        'price' => $price,
        'price_sale' => $price_sale,
        'discount' => $discount,
        'thumbnail' => "https://picsum.photos/300/300?random={$i}",
        'rating' => fake()->numberBetween(35, 50) / 10,
        'reviews_count' => fake()->numberBetween(15, 800),
        'is_new' => $i <= 5,
        'screen' => '6.7" OLED 120Hz',
        'cpu' => 'Snapdragon 8 Gen 3',
        'ram' => '12GB',
        'storage' => '256GB',
        'camera' => '108MP + 12MP + 10MP',
        'battery' => '5000mAh',
        'description' => "<p><strong>{$name}</strong> – Thiết kế cao cấp, hiệu năng mạnh mẽ, camera đỉnh cao.</p><ul><li>Màn hình: 6.7\" OLED 120Hz</li><li>Chip: Snapdragon 8 Gen 3</li><li>RAM: 12GB</li><li>Pin: 5000mAh</li></ul>",
    ];
})->all();