<?php

declare(strict_types=1);

namespace AchyutN\LaravelHLS\Traits;

use AchyutN\LaravelHLS\Observers\HLSObserver;

trait ConvertsToHls
{
    /**
     * Boot the converts to HLS trait for a model.
     */
    public static function bootConvertsToHls(): void
    {
        static::observe(app(HLSObserver::class));
    }
}
