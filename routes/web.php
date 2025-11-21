<?php

require __DIR__ . '/web/home.php';
require __DIR__ . '/web/catalog.php';
require __DIR__ . '/web/sales.php';
require __DIR__ . '/web/identity.php';
require __DIR__ . '/web/content.php';
require __DIR__ . '/web/shared.php';

Route::prefix('admin')->group(function () {
    require __DIR__ . '/admin/dashboard.php';
    require __DIR__ . '/admin/catalog.php';
    require __DIR__ . '/admin/sales.php';
    require __DIR__ . '/admin/identity.php';
    require __DIR__ . '/admin/content.php';
});
