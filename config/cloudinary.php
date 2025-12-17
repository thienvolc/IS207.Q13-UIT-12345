<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Cloudinary settings. A single CLOUDINARY_URL
    | can be used, or individual keys can be configured separately.
    |
    */

    'cloud_url' => env('CLOUDINARY_URL'),

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'di4ip4imf'),

    'api_key' => env('CLOUDINARY_API_KEY', ''),

    'api_secret' => env('CLOUDINARY_API_SECRET', ''),

    'secure' => env('CLOUDINARY_SECURE', true),

    /*
    |--------------------------------------------------------------------------
    | Upload Preset (optional)
    |--------------------------------------------------------------------------
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /*
    |--------------------------------------------------------------------------
    | Default Folder
    |--------------------------------------------------------------------------
    */
    'folder' => env('CLOUDINARY_FOLDER', 'pinkcapy'),

];
