<?php

declare(strict_types=1);

namespace AchyutN\LaravelHLS\Jobs;

use AchyutN\LaravelHLS\Actions\ConvertToHLS;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

final class QueueHLSConversion implements ShouldQueue
{
    use Queueable;

    /**
     * Indicate if the job should be marked as failed on timeout.
     */
    public bool $failOnTimeout = false;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 0; // No timeout, can run indefinitely

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Model $model
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $original_path = $this->model->getVideoPath();
        $folderName = uuid_create();

        ConvertToHLS::convertToHLS(
            $original_path,
            $folderName,
            $this->model
        );

        $this->model->setHlsPath($folderName);
        $this->model->saveQuietly();

        Storage::disk($this->model->getVideoDisk())->delete($original_path);

        $this->model->setVideoPath(null);
        $this->model->saveQuietly();
    }
}
