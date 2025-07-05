<?php

namespace AchyutN\LaravelHLS\Observers;

use AchyutN\LaravelHLS\Jobs\QueueHLSConversion;
use Illuminate\Database\Eloquent\Model;

class HLSObserver
{
    public function created(Model $model): void
    {
        $videoUploaded = $model->original_path !== null;
        if ($videoUploaded) {
            QueueHLSConversion::dispatch($model);
        }
    }

    public function updated(Model $model): void
    {
        $modelUpdated =  $model->getOriginal('original_path') !== $model->original_path;
        if ($modelUpdated) {
            $model->hls_path = null;
            $model->conversion_progress = 0;
            $model->saveQuietly();

            QueueHLSConversion::dispatch($model);
        }
    }
}
