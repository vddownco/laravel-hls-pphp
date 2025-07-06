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
        $this->mergeConfigFrom(
            dirname(__DIR__).'/src/config/hls.php',
            'hls'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            dirname(__DIR__).'/src/config/hls.php' => config_path('hls.php'),
        ], 'hls-config');
        $this->loadRoutesFrom(
            dirname(__DIR__).'/src/routes/web.php'
        );
    }
}
