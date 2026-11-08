<?php

namespace App\Providers;

use App\Services\PlaybackProgress;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer([
            'layouts.navigation',
            'components.admin-sidebar',
            'songs.index',
            'admin.player',
            'auth.login',
        ], function ($view) {
            $view->with('isOnAir', app(PlaybackProgress::class)->isOnAir());
        });
    }
}
