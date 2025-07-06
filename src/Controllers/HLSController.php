<?php

namespace AchyutN\LaravelHLS\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class HLSController
{
    public function key(Model $model, string $key): \Illuminate\Http\Response
    {
        abort_unless(request()->hasValidSignature(), 401);

        $path = "{$model->getHlsPath()}/{$model->getHLSSecretsOutputPath()}/{$key}";
        abort_unless(Storage::disk($model->getSecretsDisk())->exists($path), 404);

        return response(Storage::disk($model->getSecretsDisk())->get($path), 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $key . '"',
        ]);
    }

    public function segment(Model $model, string $filename): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        abort_unless(request()->hasValidSignature(), 401);

        $path = "{$model->getHlsPath()}/{$model->getHLSOutputPath()}/{$filename}";
        abort_unless(Storage::disk($model->getHlsDisk())->exists($path), 404);

        return response()->file(Storage::disk($model->getHlsDisk())->path($path));
    }

    public function playlist(Model $model, string $playlist = 'playlist.m3u8'): \ProtoneMedia\LaravelFFMpeg\Http\DynamicHLSPlaylist
    {
        $path = "{$model->getHlsPath()}/{$model->getHLSOutputPath()}/{$playlist}";
        abort_unless(Storage::disk($model->getHlsDisk())->exists($path), 404);

        return FFMpeg::dynamicHLSPlaylist($model->getHlsDisk())
            ->open($path)
            ->setKeyUrlResolver(fn($key) => URL::signedRoute(
                'hls.key',
                ['model' => $model, 'key' => $key]
            ))
            ->setMediaUrlResolver(fn($filename) => URL::signedRoute(
                'hls.segment',
                ['model' => $model, 'filename' => $filename]
            ))
            ->setPlaylistUrlResolver(fn($filename) => URL::signedRoute(
                'hls.playlist',
                ['model' => $model, 'playlist' => $filename]
            ));
    }
}
