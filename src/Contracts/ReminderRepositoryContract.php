<?php

namespace Spork\Reminders\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ReminderRepositoryContract
{
    public function findAllReadyToRemind(): Collection;
}
