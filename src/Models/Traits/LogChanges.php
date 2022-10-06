<?php

namespace WendellAdriel\LaravelHut\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use WendellAdriel\LaravelHut\Models\ChangeLog;

trait LogChanges
{
    public bool $disableChangeLogs = false;
    public bool $logCreateEvent    = true;
    public bool $logUpdateEvent    = true;
    public bool $logDeleteEvent    = true;

    public static function bootLogsChanges()
    {
        static::saved(function (Model $model) {
            if ($model->disableChangeLogs) {
                return;
            }

            if ($model->wasRecentlyCreated && $model->logCreateEvent) {
                static::logChange($model, ChangeLog::ACTION_CREATE);
            } else {
                if (!$model->getChanges()) {
                    return;
                }
                if ($model->logUpdateEvent) {
                    static::logChange($model, ChangeLog::ACTION_UPDATE);
                }
            }
        });

        static::deleted(function (Model $model) {
            if ($model->logDeleteEvent) {
                static::logChange($model, ChangeLog::ACTION_DELETE);
            }
        });
    }

    /**
     * @param Model  $model
     * @param string $action
     * @return void
     */
    public static function logChange(Model $model, string $action): void
    {
        ChangeLog::query()->create([
            'user_id'      => Auth::check() ? Auth::user()->id : 0,
            'record_id'    => empty($model->id) ? 0 : $model->id,
            'table'        => $model->getTable(),
            'action'       => $action,
            'message'      => static::logSubject($model),
            'new_data'     => $action !== ChangeLog::ACTION_DELETE ? json_encode($model->getAttributes()) : null,
            'old_data'     => $action !== ChangeLog::ACTION_CREATE ? json_encode($model->getOriginal()) : null,
            'changed_data' => $action === ChangeLog::ACTION_UPDATE ? json_encode($model->getChanges()) : null,
        ]);
    }

    /**
     * @param Model $model
     * @return string
     */
    public static function logSubject(Model $model): string
    {
        return $model->toJson();
    }
}
