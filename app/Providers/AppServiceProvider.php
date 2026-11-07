<?php

namespace App\Providers;

use App\Models\Schedule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share status on-air ke semua view secara global
        View::composer('*', function ($view) {
            $isOnAir = Schedule::where('is_active', true)
                ->get()
                ->contains(fn ($s) => $s->isNowActive());

            $view->with('isOnAir', $isOnAir);
        });
    }
}
