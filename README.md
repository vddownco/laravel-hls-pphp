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
