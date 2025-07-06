# Laravel HLS

A Laravel package for generating HLS (HTTP Live Streaming) playlists and segments with AES-128 encryption.

This package makes use of the [laravel-ffmpeg](https://github.com/protonemedia/laravel-ffmpeg) package to handle video processing and
conversion to HLS format. It provides a simple way to convert video files stored in your Laravel application into HLS streams, which can be used for adaptive bitrate streaming.

## Installation

You can install the package via Composer:

```bash
composer require achyutn/laravel-hls
```

**Optional:** You can publish the [configuration file](src/config/hls.php) using the following command:

```bash
php artisan vendor:publish --provider="AchyutN\LaravelHLS\HLSProvider" --tag="hls-config"
```

## Usage

You just need to add the `ConvertsToHls` trait to your model. The package will automatically handle the conversion of
your video files to HLS format.

```php
<?php

namespace App\Models;

use AchyutN\LaravelHLS\Traits\ConvertsToHls;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use ConvertsToHls;
}
```

### Fetch HLS playlist

To fetch the HLS playlist for a video, you can call the endpoint `/hls/{model}/playlist` or `route('hls.playlist', ['model' => $model])` where `$model` is an instance of your model that uses the `ConvertsToHls` trait. This will return the HLS playlist in M3U8 format.

```php
use App\Models\Video;

// Fetch the HLS playlist for a video
$video = Video::find(1);
$playlistUrl = route('hls.playlist', ['model' => $video]);
```

## Configuration

### Global Configuration

You can configure the package by editing the `config/hls.php` file. Below are the available options:

| Key                   | Description                                                                                   | Type     | Default               |
|-----------------------|-----------------------------------------------------------------------------------------------|----------|-----------------------|
| `video_column`        | The database column that stores the original video path.                                      | `string` | `video_path`          |
| `hls_column`          | The database column that stores the path to the HLS output folder.                            | `string` | `hls_path`            |
| `progress_column`     | The database column that stores the conversion progress percentage.                           | `string` | `conversion_progress` |
| `video_disk`          | The filesystem disk where original video files are stored. Refer to `config/filesystems.php`. | `string` | `public`              |
| `hls_disk`            | The filesystem disk where HLS output files are stored. Refer to `config/filesystems.php`.     | `string` | `public`              |
| `secrets_disk`        | The filesystem disk where encryption secrets are stored.                                      | `string` | `public`              |
| `hls_output_path`     | Path relative to `hls_disk` where HLS files are saved.                                        | `string` | `hls`                 |
| `secrets_output_path` | Path relative to `secrets_disk` where encryption secrets are saved.                           | `string` | `secrets`             |

> 💡 Tip: All disk values must be valid disks defined in your `config/filesystems.php`.

### Model-Level Configuration

You can override any global setting on a **per-model basis** by defining public properties in your Eloquent model. These
override values will be used instead of the global config.

| Property                | Description                                                                       | Type     |
|-------------------------|-----------------------------------------------------------------------------------|----------|
| `$videoColumn`          | Overrides `video_column` from config. Path to the original video file.            | `string` |
| `$hlsColumn`            | Overrides `hls_column`. Path to the generated HLS folder.                         | `string` |
| `$progressColumn`       | Overrides `progress_column`. Stores HLS conversion progress.                      | `string` |
| `$videoDisk`            | Overrides `video_disk`. Disk name for the original video.                         | `string` |
| `$hlsDisk`              | Overrides `hls_disk`. Disk name for the HLS output.                               | `string` |
| `$secretsDisk`          | Overrides `secrets_disk`. Disk for storing encryption keys.                       | `string` |
| `$hlsOutputPath`        | Overrides `hls_output_path`. Path to store HLS files relative to `hlsDisk`.       | `string` |
| `$hlsSecretsOutputPath` | Overrides `secrets_output_path`. Path to store secrets relative to `secretsDisk`. | `string` |

#### Example

```php
use AchyutN\LaravelHLS\Traits\ConvertsToHls;

class CustomVideo extends Model
{
    use ConvertsToHls;

    public string $videoColumn = 'original_video';
    public string $hlsColumn = 'hls_output';
    public string $progressColumn = 'conversion_percent';

    public string $videoDisk = 'videos';
    public string $hlsDisk = 'hls-outputs';
    public string $secretsDisk = 'secure';

    public string $hlsOutputPath = 'streamed/hls';
    public string $hlsSecretsOutputPath = 'streamed/secrets';
}
```

## Features

- [x] Convert video files to HLS format with AES-128 encryption.
- [x] Store HLS segments and playlists in a specified directory.
- [x] Track conversion progress using a dedicated database column.
- [x] Easily integrate with Eloquent models using a trait.
- [x] Configurable paths for video files, HLS output, and encryption secrets.
- [x] Supports custom disk configurations for video and HLS storage.
- [x] Model-level configuration overrides for flexibility.
- [x] Routes for accessing HLS playlist.
- [ ] Customizable resolutions and bitrate settings.
- [ ] Support for multiple video formats.
- [ ] Separate job for each resolution.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Changelog

See the [CHANGELOG](CHANGELOG.md) for details on changes made in each version.

## Contributing

Contributions are welcome! Please create a pull request or open an issue if you find any bugs or have feature requests.

## Support

If you find this package useful, please consider starring the repository on GitHub to show your support.
