<?php

namespace AchyutN\LaravelHLS\Traits;

use AchyutN\LaravelHLS\Observers\HLSObserver;

trait ConvertsToHls
{
    /**
     * Boot the converts to HLS trait for a model.
     *
     * @return void
     */
    public static function bootConvertsToHls(): void
    {
        static::observe(app(HLSObserver::class));
    }
}
