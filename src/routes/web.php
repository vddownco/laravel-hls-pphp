<?php

declare(strict_types=1);

Route::group([
    'controller' => AchyutN\LaravelHLS\Controllers\HLSController::class,
    'as' => 'hls.',
    'prefix' => 'hls',
], function (): void {
    Route::get('/key/{model}/{id}/{key}', 'key')->name('key');
    Route::get('/segment/{model}/{id}/{filename}', 'segment')->name('segment');
    Route::get('/{model}/{id}/playlist/{playlist?}', 'playlist')->name('playlist');
});
