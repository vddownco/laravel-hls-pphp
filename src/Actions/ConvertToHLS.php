<?php

namespace AchyutN\LaravelHLS\Actions;

use App\Jobs\UpdateConversionProgress;
use Exception;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;

class ConvertToHLS
{
    /**
     * Convert a video file to HLS format with AES-128 encryption.
     *
     * @param string $inputPath The path to the input video file.
     * @param string $outputFolder The folder where the HLS output will be stored.
     * @param Model $model The model instance (optional, used for progress tracking).
     * @return void
     * @throws Exception If the conversion fails.
     */
    public static function convertToHLS(string $inputPath, string $outputFolder, Model $model): void
    {
        $resolutions = [
            '480p' => '854x480',
            '720p' => '1280x720',
            '1080p' => '1920x1080',
            '1440p' => '2560x1440',
            '2160p' => '3840x2160',
        ];

//        $kiloBitRates = [
//            '480p'  => 1000,
//            '720p'  => 1500,
//            '1080p' => 2500,
//            '1440p' => 4000,
//            '2160p' => 6000,
//        ];

        $kiloBitRates = [
            '480p'  => 750,
            '720p'  => 1000,
            '1080p' => 1500,
            '1440p' => 2500,
            '2160p' => 4000,
        ];

        $fileBitrate = \FFMpeg\FFProbe::create()->format(Storage::disk('public')->path($inputPath))->get('bit_rate')/1000;
        $videos =  \FFMpeg\FFProbe::create()->streams(Storage::disk('public')->path($inputPath))->videos()->first();
        $fileResolution = $videos ? $videos->get('width') . 'x' . $videos->get('height') : null;

        $formats = [];

        $lowerResolutions = array_filter($resolutions, function ($resolution) use ($fileResolution) {
            return self::extractResolution($resolution)['height'] <= self::extractResolution($fileResolution)['height'];
        });

        foreach ($lowerResolutions as $resolution => $res) {
            $bitrate = $kiloBitRates[$resolution] ?? 1000;
            $formats[] = (new X264)
                ->setKiloBitrate($bitrate)
                ->setAdditionalParameters([
                    '-vf', "scale=" . self::renameResolution($res)
                ]);
        }

        if (empty($formats)) {
            $formats[] = (new X264)
                ->setKiloBitrate($fileBitrate)
                ->setAdditionalParameters([
                    '-vf', "scale=" . self::renameResolution($fileResolution)
                ]);
        }

        $export = FFMpeg::fromDisk('public')
            ->open($inputPath)
            ->exportForHLS()
            ->toDisk('local');

        foreach ($formats as $format) {
            $export->addFormat($format);
        }

        info("Started conversion for resolutions: " . implode(', ', array_keys($lowerResolutions)));

        $progress = progress(
            label: "Converting video to HLS format...",
            steps: 100,
            hint: "This may take a while, depending on the video length and resolution."
        );
        $progress->start();

        $export->onProgress(function ($percentage) use ($model, $progress) {
            if (isset($model)) {
                $progress->advance();
                UpdateConversionProgress::dispatch($model, $percentage);
            }
        });

        $export->withRotatingEncryptionKey(function ($filename, $contents) use ($outputFolder) {
            Storage::disk('secrets')->put("{$outputFolder}/{$filename}", $contents);
        })
            ->save("{$outputFolder}/playlist.m3u8");
        $progress->finish();
    }

    /**
     * Extract width and height from a resolution string.
     *
     * @param string $resolution The resolution string in the format '{width}x{height}'.
     * @return array An associative array with 'width' and 'height' keys.
     * @throws Exception If the resolution string is not in the correct format.
     */
    private static function extractResolution(string $resolution): array
    {
        if (preg_match('/^(\d+)x(\d+)$/', $resolution, $matches)) {
            return [
                'width' => (int)$matches[1],
                'height' => (int)$matches[2],
            ];
        }

        throw new Exception("Invalid resolution format: {$resolution}. Expected format is '{width}x{height}'.");
    }

    /**
     * Rename resolution from '{width}x{height}' to 'width:height'.
     *
     * @param string $resolution The resolution string in the format '{width}x{height}'.
     * @return string The resolution string in the format 'width:height'.
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
