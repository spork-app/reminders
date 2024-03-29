<?php

namespace Spork\Reminders\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spork\Core\Models\FeatureList;
use Spork\Reminders\Contracts\ReminderRepositoryContract;

class ReminderRepository implements ReminderRepositoryContract
{
    public function __construct(public FeatureList $model)
    {
    }

    public function findAllReadyToRemind(): Collection
    {
        $now = now();

        return $this->model->newModelQuery()->where(function (Builder $query) {
            $query->where('settings->should_repeat', true);
        })
        ->orWhere(function (Builder $query) use ($now) {
            $query->where('settings->remind_at', '<=', $now->copy()->endOfMinute())
                ->where('settings->remind_at', '>=', $now->copy()->startOfMinute())
                ->where('settings->should_repeat', false);
        })->get();
    }
}
