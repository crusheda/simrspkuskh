<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use App\Hashing\CustomHasher;
use App\Services\LibreOfficeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LibreOfficeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Hash::extend('custom_hmac_bcrypt', function () {
            return new CustomHasher;
        });
    }
}
