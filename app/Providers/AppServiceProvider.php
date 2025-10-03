<?php

namespace App\Providers;

use App\Models\KeranjangPeminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $countKeranjang = KeranjangPeminjaman::where('user_id', Auth::id())->count();
                $view->with('countKeranjang', $countKeranjang);
            } else {
                $view->with('countKeranjang', 0);
            }
        });
    }
}
