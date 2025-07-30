<?php

namespace AchyutN\LaravelHLS\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class HLSService
{
    public function getKey(string $model, int|string $id, string $key): Response
    {
        $resolvedModel = $this->resolveModel($model)->query()->findOrFail($id);

        $path = "{$resolvedModel->getHlsPath()}/{$resolvedModel->getHLSSecretsOutputPath()}/{$key}";
        if (!Storage::disk($resolvedModel->getSecretsDisk())->exists($path)) {
            abort(404);
        }

        return response(Storage::disk($resolvedModel->getSecretsDisk())->get($path), 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="'.$key.'"',
        ]);
    }

    public function getSegment(string $model, int|string $id, string $filename): BinaryFileResponse
    {
        $resolvedModel = $this->resolveModel($model)->query()->findOrFail($id);

        $path = "{$resolvedModel->getHlsPath()}/{$resolvedModel->getHLSOutputPath()}/{$filename}";
        if (!Storage::disk($resolvedModel->getHlsDisk())->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk($resolvedModel->getHlsDisk())->path($path));
    }

    public function getPlaylist(string $model, int|string $id, string $playlist = 'playlist.m3u8'): \ProtoneMedia\LaravelFFMpeg\Http\DynamicHLSPlaylist
    {
        $resolvedModel = $this->resolveModel($model)->query()->findOrFail($id);

        $path = "{$resolvedModel->getHlsPath()}/{$resolvedModel->getHLSOutputPath()}/{$playlist}";
        if (!Storage::disk($resolvedModel->getHlsDisk())->exists($path)) {
            abort(404);
        }

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
        } catch (\Throwable $e) {
            Log::error("Failed to resolve model for type [{$type}]: " . $e->getMessage());
            abort(404, "Unknown model type [{$type}]");
        }
    }
}
