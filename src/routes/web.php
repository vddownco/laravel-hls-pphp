<?php

declare(strict_types=1);

if (config('hls.register_routes', true)) {
    Route::group([
        'controller' => AchyutN\LaravelHLS\Controllers\HLSController::class,
        'as' => 'hls.',
        'prefix' => 'hls',
        'middleware' => config('hls.middlewares', []),
    ], function (): void {
        Route::get('/key/{model}/{id}/{key}', 'key')->name('key');
        Route::get('/segment/{model}/{id}/{filename}', 'segment')->name('segment');
        Route::get('/{model}/{id}/playlist/{playlist?}', 'playlist')->name('playlist');
    });
}
