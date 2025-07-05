<?php

namespace AchyutN\LaravelHLS\Jobs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;

class UpdateConversionProgress
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Model $model,
        private readonly string $percentage = '0',
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Model::withoutTimestamps(function () {
            $this->model->updateQuietly([
                'conversion_progress' => $this->percentage,
            ]);
        });
    }
}
