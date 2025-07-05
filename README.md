# Laravel HLS

A Laravel package for generating HLS (HTTP Live Streaming) playlists and segments with AES-128 encryption.

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

You just need to add the `ConvertsToHls` trait to your model. The package will automatically handle the conversion of your video files to HLS format.

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

## Configuration

## Configuration

You can configure the package by editing the `config/hls.php` file. Below are the available options:

| Key                   | Description                                                                                     | Type     | Default         |
|------------------------|-------------------------------------------------------------------------------------------------|----------|-----------------|
| `video_column`         | The database column that stores the original video path.                                        | `string` | `video_path`    |
| `hls_column`           | The database column that stores the path to the HLS output folder.                              | `string` | `hls_path`      |
| `progress_column`      | The database column that stores the conversion progress percentage.                             | `string` | `conversion_progress` |
| `video_disk`           | The filesystem disk where original video files are stored. Refer to `config/filesystems.php`.  | `string` | `public`        |
| `hls_disk`             | The filesystem disk where HLS output files are stored. Refer to `config/filesystems.php`.      | `string` | `public`        |
| `secrets_disk`         | The filesystem disk where encryption secrets are stored.                                        | `string` | `public`         |
| `hls_output_path`      | Path relative to `hls_disk` where HLS files are saved.                                          | `string` | `hls`           |
| `secrets_output_path`  | Path relative to `secrets_disk` where encryption secrets are saved.                             | `string` | `secrets`       |

> 💡 Tip: All disk values must be valid disks defined in your `config/filesystems.php`.
