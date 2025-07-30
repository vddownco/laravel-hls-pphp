<?php

declare(strict_types=1);

namespace AchyutN\LaravelHLS\Controllers;

use AchyutN\LaravelHLS\Services\HLSService;

final class HLSController
{
    public function __construct(private readonly HLSService $service) {}

    public function key(string $model, int|string $id, string $key)
    {
        abort_unless(request()->hasValidSignature(), 401);
        return $this->service->getKey($model, $id, $key);
    }

    public function segment(string $model, int|string $id, string $filename)
    {
        abort_unless(request()->hasValidSignature(), 401);
        return $this->service->getSegment($model, $id, $filename);
    }

    public function playlist(string $model, int|string $id, string $playlist = 'playlist.m3u8')
    {
        return $this->service->getPlaylist($model, $id, $playlist);
    }
}
