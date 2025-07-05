<?php

declare(strict_types=1);

namespace AchyutN\LaravelHLS;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class HLSProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/hls.php', 'hls');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/hls.php' => config_path('hls.php'),
        ], 'hls-config');
    }
}
