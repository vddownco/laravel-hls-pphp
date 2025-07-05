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

    public function getVideoColumn(): string
    {
        return property_exists($this, 'videoColumn') ? config('hls.video_column') : 'video_path';
    }

    public function getHlsColumn(): string
    {
        return property_exists($this, 'hlsColumn') ? config('hls.hls_column') : 'hls_path';
    }

    public function getProgressColumn(): string
    {
        return property_exists($this, 'progressColumn') ? config('hls.progress_column') : 'conversion_progress';
    }

    public function getVideoDisk(): string
    {
        return property_exists($this, 'videoDisk') ? config('hls.video_disk') : 'public';
    }

    public function getHlsDisk(): string
    {
        return property_exists($this, 'hlsDisk') ? config('hls.hls_disk') : 'public';
    }

    public function getSecretDisk(): string
    {
        return property_exists($this, 'secretDisk') ? config('hls.secret_disk') : 'public';
    }

    public function getHLSOutputPath(): string
    {
        return property_exists($this, 'hlsOutputPath') ? config('hls.hls_output_path') : 'hls';
    }

    public function getHLSSecretOutputPath(): string
    {
        return property_exists($this, 'hlsSecretOutputPath') ? config('hls.secret_output_path') : 'hls/secrets';
    }
}
