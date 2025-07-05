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
