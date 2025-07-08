<?php

declare(strict_types=1);

namespace AchyutN\LaravelHLS\Actions;

use AchyutN\LaravelHLS\Generators\CustomGenerator;
use AchyutN\LaravelHLS\Jobs\UpdateConversionProgress;
use Exception;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;

final class ConvertToHLS
{
    /**
     * Convert a video file to HLS format with AES-128 encryption.
     *
     * @param  string  $inputPath  The path to the input video file.
     * @param  string  $outputFolder  The folder where the HLS output will be stored.
     * @param  Model  $model  The model instance (optional, used for progress tracking).
     *
     * @throws Exception If the conversion fails.
     */
    public static function convertToHLS(string $inputPath, string $outputFolder, Model $model): void
    {
        $resolutions = config('hls.resolutions');
        $kiloBitRates = config('hls.bitrates');

        $videoDisk = $model->getVideoDisk();
        $hlsDisk = $model->getHlsDisk();
        $secretsDisk = $model->getSecretsDisk();
        $hlsOutputPath = $model->getHLSOutputPath();
        $secretsOutputPath = $model->getHLSSecretsOutputPath();

        $media = FFMpeg::fromDisk($videoDisk)->open($inputPath);
        $fileBitrate = $media->getFormat()->get('bit_rate') / 1000;
        $streamVideo = $media->getVideoStream()->getDimensions();
        $fileResolution = "{$streamVideo->getWidth()}x{$streamVideo->getHeight()}";

        $formats = [];

        $lowerResolutions = array_filter($resolutions, fn ($resolution): bool => self::extractResolution($resolution)['height'] <= self::extractResolution($fileResolution)['height']);

        foreach ($lowerResolutions as $resolution => $res) {
            $bitrate = $kiloBitRates[$resolution] ?? 1000;
            $formats[] = (new X264)
                ->setKiloBitrate($bitrate)
                ->setAdditionalParameters([
                    '-vf', 'scale='.self::renameResolution($res),
                    '-preset', 'veryfast',
                    '-profile:v', 'main',
                    '-bufsize', '3000k',
                    '-maxrate', '2000k',
                ]);
        }

        if ($formats === []) {
            $formats[] = (new X264)
                ->setKiloBitrate($fileBitrate)
                ->setAdditionalParameters([
                    '-vf', 'scale='.self::renameResolution($fileResolution),
                    '-preset', 'veryfast',
                    '-profile:v', 'main',
                    '-bufsize', '3000k',
                    '-maxrate', '2000k',
                ]);
        }

        $export = FFMpeg::fromDisk($videoDisk)
            ->open($inputPath)
            ->exportForHLS()
            ->toDisk($hlsDisk);

        foreach ($formats as $format) {
            $export->addFormat($format);
        }

        info('Started conversion for resolutions: '.implode(', ', array_keys($lowerResolutions)));

        $progress = progress(
            label: 'Converting video to HLS format...',
            steps: 100,
            hint: 'This may take a while, depending on the video length and resolution.'
        );
        $progress->start();

        $export->onProgress(function ($percentage) use ($model, $progress): void {
            $progress->advance();
            UpdateConversionProgress::dispatch($model, $percentage);
        });

        $export
            ->withRotatingEncryptionKey(function ($filename, $contents) use ($outputFolder, $secretsDisk, $secretsOutputPath): void {
                Storage::disk($secretsDisk)->put("{$outputFolder}/{$secretsOutputPath}/{$filename}", $contents);
            })
            ->save("{$outputFolder}/{$hlsOutputPath}/playlist.m3u8");

        $progress->finish();
    }

    /**
     * Extract width and height from a resolution string.
     *
     * @param  string  $resolution  The resolution string in the format '{width}x{height}'.
     * @return array An associative array with 'width' and 'height' keys.
     *
     * @throws Exception If the resolution string is not in the correct format.
     */
    private static function extractResolution(string $resolution): array
    {
        if (preg_match('/^(\d+)x(\d+)$/', $resolution, $matches)) {
            return [
                'width' => (int) $matches[1],
                'height' => (int) $matches[2],
            ];
        }

        throw new Exception("Invalid resolution format: {$resolution}. Expected format is '{width}x{height}'.");
    }

    /**
     * Rename resolution from '{width}x{height}' to 'width:height'.
     *
     * @param  string  $resolution  The resolution string in the format '{width}x{height}'.
     * @return string The resolution string in the format 'width:height'.
     *
     * @throws Exception
     */
    private static function renameResolution(string $resolution): string
    {
        $parts = explode('x', $resolution);
        if (count($parts) !== 2) {
            throw new Exception("Invalid resolution format: {$resolution}. Expected format is '{width}x{height}'.");
        }

        return "{$parts[0]}:{$parts[1]}";
    }
}
