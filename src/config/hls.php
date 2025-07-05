<?php

declare(strict_types=1);

return [
    /**
     * The database column that is used to store the original video path.
     * This should be a string column that contains the path to the original
     * video file in default storage.
     *
     * Default: 'video_path'
     */
    'video_column' => 'video_path',

    /**
     * The database column that is used to store the HLS folder.
     * This should be a string column that contains the path to the HLS
     * output folder in default storage.
     *
     * Default: 'hls_path'
     */
    'hls_column' => 'hls_path',

    /**
     * The database column that is used to store the conversion progress.
     * This should be an integer column that contains the progress percentage
     * of the HLS conversion.
     *
     * Default: 'conversion_progress'
     */
    'progress_column' => 'conversion_progress',

    /**
     * The disk where the original video files are stored.
     * This should be a valid disk name as defined in your
     * `config/filesystems.php` file.
     *
     * Default: 'public'
     */
    'video_disk' => 'public',

    /**
     * The disk where the HLS output files are stored.
     * This should be a valid disk name as defined in your
     * `config/filesystems.php` file.
     *
     * Default: 'public'
     */
    'hls_disk' => 'public',

    /**
     * The disk where the encryption secrets are stored.
     * This should be a valid disk name as defined in your
     * `config/filesystems.php` file.
     *
     * Default: 'public'
     */
    'secrets_disk' => 'local',

    /**
     * The path where the HLS output files will be stored.
     * This should be a valid path relative to the disk defined
     * in `hls_disk`.
     *
     * Default: 'hls'
     */
    'hls_output_path' => 'hls',

    /**
     * The path where the encryption secrets will be stored.
     * This should be a valid path relative to the disk defined
     * in `secret_disk`.
     *
     * Default: 'hls/secrets'
     */
    'secrets_output_path' => 'secrets',
];
