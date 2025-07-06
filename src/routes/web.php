<?php

declare(strict_types=1);

Route::group([
    'controller' => \AchyutN\LaravelHLS\Controllers\HLSController::class,
    'as' => 'hls.',
    'prefix' => 'hls',
    'middleware' => ['auth'],
], function () {
    Route::get('/key/{model}/{key}', 'key')->name('key');
    Route::get('/segment/{model}/{filename}', 'segment')->name('segment');
    Route::get('/{model}/playlist/{playlist?}', 'playlist')->name('playlist');
});
