<?php

declare(strict_types=1);

namespace AchyutN\LaravelHLS\Jobs;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;

final class UpdateConversionProgress
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Model $model,
        private readonly string $percentage = '0',
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Model::withoutTimestamps(function (): void {
                $this->model->setProgress((int) $this->percentage);
                $this->model->saveQuietly();
            });
        } catch (Exception $e) {
            abort(500, 'Failed to update conversion progress: '.$e->getMessage());
        }
    }
}
