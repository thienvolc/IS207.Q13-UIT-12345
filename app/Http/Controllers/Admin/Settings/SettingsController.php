<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Domains\Common\Entities\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // Config type constants
    private const TYPE_STRING = 1;
    private const TYPE_INT = 2;
    private const TYPE_BOOL = 3;
    private const TYPE_JSON = 4;

    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = $this->getAllSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // General
            'shop_name' => 'required|string|max:255',
            'shop_email' => 'required|email|max:255',
            'shop_phone' => 'nullable|string|max:20',
            'shop_address' => 'nullable|string|max:500',
            'shop_logo' => 'nullable|url|max:500',

            // Payment
            'currency' => 'required|string|in:VND,USD',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'string|in:cod,bank_transfer,vnpay,momo',

            // Shipping
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'default_shipping_fee' => 'nullable|numeric|min:0',

            // Social
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',

            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        // Save each setting to database
        foreach ($validated as $key => $value) {
            $this->saveSetting($key, $value);
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Cài đặt đã được lưu thành công!');
    }

    /**
     * Get all settings from database with defaults.
     */
    private function getAllSettings(): array
    {
        $defaults = [
            // General
            'shop_name' => 'PinkCapy Shop',
            'shop_email' => 'contact@pinkcapy.com',
            'shop_phone' => '0123 456 789',
            'shop_address' => '123 Đường ABC, Quận 1, TP.HCM',
            'shop_logo' => '',

            // Payment
            'currency' => 'VND',
            'payment_methods' => ['cod', 'bank_transfer'],

            // Shipping
            'free_shipping_threshold' => 500000,
            'default_shipping_fee' => 30000,

            // Social
            'facebook_url' => '',
            'instagram_url' => '',
            'youtube_url' => '',

            // SEO
            'meta_title' => 'PinkCapy - Shop thời trang',
            'meta_description' => 'PinkCapy - Shop thời trang online uy tín, chất lượng',
        ];

        // Get all configs from database
        $configs = Config::all()->keyBy('key');

        // Merge with defaults
        $settings = [];
        foreach ($defaults as $key => $default) {
            if ($configs->has($key)) {
                $config = $configs->get($key);
                $settings[$key] = $this->parseValue($config->value, $config->type);
            } else {
                $settings[$key] = $default;
            }
        }

        return $settings;
    }

    /**
     * Save a single setting to database.
     */
    private function saveSetting(string $key, mixed $value): void
    {
        $type = self::TYPE_STRING;
        $storedValue = $value;

        // Determine type and serialize value
        if (is_array($value)) {
            $type = self::TYPE_JSON;
            $storedValue = json_encode($value);
        } elseif (is_bool($value)) {
            $type = self::TYPE_BOOL;
            $storedValue = $value ? '1' : '0';
        } elseif (is_numeric($value) && !is_string($value)) {
            $type = self::TYPE_INT;
            $storedValue = (string) $value;
        } else {
            $storedValue = (string) $value;
        }

        Config::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'type' => $type,
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Parse stored value based on type.
     */
    private function parseValue(?string $value, ?int $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            self::TYPE_INT => (int) $value,
            self::TYPE_BOOL => $value === '1',
            self::TYPE_JSON => json_decode($value, true) ?? [],
            default => $value,
        };
    }
}
