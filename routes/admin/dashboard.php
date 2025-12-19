<?php

use App\Http\Controllers\Admin\Catalog\CategoryController;
use App\Http\Controllers\Admin\Catalog\ProductController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Identity\CustomerController;
use App\Http\Controllers\Admin\Inventory\InventoryController;
use App\Http\Controllers\Admin\Sales\OrderController;
use App\Http\Controllers\Admin\Settings\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/chart-data', [DashboardController::class, 'chartData'])
            ->name('dashboard.chart-data');

        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);

        Route::get('inventory', [InventoryController::class, 'index'])
            ->name('inventory.index');
        Route::post('inventory/{id}/adjust', [InventoryController::class, 'adjust'])
            ->name('inventory.adjust');

        Route::resource('orders', OrderController::class)
            ->only(['index', 'show', 'update']);

        Route::resource('customers', CustomerController::class)
            ->only(['index', 'show']);

        // Transactions Management
        Route::resource('transactions', \App\Http\Controllers\Admin\Sales\TransactionController::class)
            ->only(['index', 'show']);

        Route::get('settings', [SettingsController::class, 'index'])
            ->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])
            ->name('settings.update');

        // Reports
        Route::get('reports', [\App\Http\Controllers\Admin\Report\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/revenue-data', [\App\Http\Controllers\Admin\Report\ReportController::class, 'revenueData'])->name('reports.revenue');
        Route::get('reports/top-products-data', [\App\Http\Controllers\Admin\Report\ReportController::class, 'topProductsData'])->name('reports.top-products');
        Route::get('reports/customer-data', [\App\Http\Controllers\Admin\Report\ReportController::class, 'customerData'])->name('reports.customers');

        // Exports
        Route::get('reports/export/products', [\App\Http\Controllers\Admin\Report\ReportController::class, 'exportProducts'])->name('reports.export.products');
        Route::get('reports/export/orders', [\App\Http\Controllers\Admin\Report\ReportController::class, 'exportOrders'])->name('reports.export.orders');
        Route::get('reports/export/revenue-pdf', [\App\Http\Controllers\Admin\Report\ReportController::class, 'exportRevenuePdf'])->name('reports.export.revenue-pdf');
        Route::get('reports/export/customers', [\App\Http\Controllers\Admin\Report\ReportController::class, 'exportCustomers'])->name('reports.export.customers');
        Route::get('reports/export/transactions', [\App\Http\Controllers\Admin\Report\ReportController::class, 'exportTransactions'])->name('reports.export.transactions');

        // Blog Posts Management
        Route::resource('blogs', \App\Http\Controllers\Admin\Content\BlogController::class);

    });
