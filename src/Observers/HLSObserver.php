<?php

declare(strict_types=1);

namespace AchyutN\LaravelHLS\Observers;

use AchyutN\LaravelHLS\Jobs\QueueHLSConversion;
use Illuminate\Database\Eloquent\Model;

final class HLSObserver
{
    public function created(Model $model): void
    {
        $videoUploaded = ! empty($model->getVideoPath()) && $model->getVideoPath() !== 'null';
        if ($videoUploaded) {
            QueueHLSConversion::dispatch($model)->onQueue(config('hls.queue_name', 'default'));
        }
    }

    public function updated(Model $model): void
    {
        $modelUpdated = $model->getOriginal($model->getVideoColumn()) !== $model->getVideoPath();
        if ($modelUpdated) {
            $model->setHlsPath(null);
            $model->setProgress(0);

            $model->saveQuietly();

            QueueHLSConversion::dispatch($model)->onQueue(config('hls.queue_name', 'default'));
        }
    }
}
