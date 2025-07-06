<?php

declare(strict_types=1);

namespace AchyutN\LaravelHLS\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

final class HLSController
{
    public function key(string $model, int $id, string $key): \Illuminate\Http\Response
    {
        abort_unless(request()->hasValidSignature(), 401);

        $resolvedModel = $this->resolveModel($model)->query()->findOrFail($id);

        $path = "{$resolvedModel->getHlsPath()}/{$resolvedModel->getHLSSecretsOutputPath()}/{$key}";
        abort_unless(Storage::disk($resolvedModel->getSecretsDisk())->exists($path), 404);

        return response(Storage::disk($resolvedModel->getSecretsDisk())->get($path), 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="'.$key.'"',
        ]);
    }

    public function segment(string $model, int $id, string $filename): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        abort_unless(request()->hasValidSignature(), 401);

        $resolvedModel = $this->resolveModel($model)->query()->findOrFail($id);

        $path = "{$resolvedModel->getHlsPath()}/{$resolvedModel->getHLSOutputPath()}/{$filename}";
        abort_unless(Storage::disk($resolvedModel->getHlsDisk())->exists($path), 404);

        return response()->file(Storage::disk($resolvedModel->getHlsDisk())->path($path));
    }

    public function playlist(string $model, int $id, string $playlist = 'playlist.m3u8'): \ProtoneMedia\LaravelFFMpeg\Http\DynamicHLSPlaylist
    {
        $resolvedModel = $this->resolveModel($model)->query()->findOrFail($id);
        $path = "{$resolvedModel->getHlsPath()}/{$resolvedModel->getHLSOutputPath()}/{$playlist}";
        abort_unless(Storage::disk($resolvedModel->getHlsDisk())->exists($path), 404);

        return FFMpeg::dynamicHLSPlaylist($resolvedModel->getHlsDisk())
            ->open($path)
            ->fromDisk($resolvedModel->getHlsDisk())
            ->setKeyUrlResolver(fn ($key) => URL::signedRoute(
                'hls.key',
                ['model' => $model, 'id' => $id, 'key' => $key]
            ))
            ->setMediaUrlResolver(fn ($filename) => URL::signedRoute(
                'hls.segment',
                ['model' => $model, 'id' => $id, 'filename' => $filename]
            ))
            ->setPlaylistUrlResolver(fn ($filename) => URL::signedRoute(
                'hls.playlist',
                ['model' => $model, 'id' => $id, 'playlist' => $filename]
            ));
    }

    private function resolveModel(string $type): Model
    {
        try {
            return app(config('hls.model_aliases')[$type]);
        } catch (Exception $e) {
            Log::error("Failed to resolve model for type [{$type}]. Add a model_aliases entry in your `hls.php` config file.");
            abort(404, "Unknown model type [{$type}]: ".$e->getMessage());
        }
    }
}
