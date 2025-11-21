<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share categories chỉ với header
        view()->composer('partials.header', function ($view) {
            // Cache trong memory của request (không cần DB cache)
            static $categories = null;
            
            if ($categories === null) {
                $categories = \Illuminate\Support\Facades\DB::table('categories')
                    ->select('category_id', 'title', 'slug')
                    ->whereNull('parent_id') // Chỉ lấy category cha
                    ->orderBy('title')
                    ->get();
            }
            
            $view->with('globalCategories', $categories);
        });
    }
}
