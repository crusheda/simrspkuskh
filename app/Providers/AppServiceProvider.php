<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        /*
        |--------------------------------------------------------------------------
        | Directive: hasJenisRole
        |--------------------------------------------------------------------------
        | Mengecek satu jenis user.
        | Contoh: @hasJenisRole(1) untuk Dokter
        */

        Blade::if('hasJenisRole', function ($jenisUser) {

            if (!Auth::check()) {
                return false;
            }

            $user = Auth::user();

            return DB::table('simrspku_klaim.model_has_roles as mhr')
                ->join('simrspku_klaim.roles as r', 'r.id', '=', 'mhr.role_id')
                ->where('mhr.model_id', $user->ID)
                ->where('mhr.model_type', $user->getMorphClass())
                ->where('r.jenis', $jenisUser)
                ->exists();
        });

        /*
        |--------------------------------------------------------------------------
        | Directive: hasMultipleJenisRole
        |--------------------------------------------------------------------------
        | Mengecek beberapa jenis user.
        | Contoh: @hasMultipleJenisRole([1, 2])
        */

        Blade::if('hasMultipleJenisRole', function ($jenisUser) {

            if (!Auth::check()) {
                return false;
            }

            $user = Auth::user();

            return DB::table('simrspku_klaim.model_has_roles as mhr')
                ->join('simrspku_klaim.roles as r', 'r.id', '=', 'mhr.role_id')
                ->where('mhr.model_id', $user->ID)
                ->where('mhr.model_type', get_class($user))
                ->whereIn('r.jenis', (array) $jenisUser)
                ->exists();
        });
    }
}
