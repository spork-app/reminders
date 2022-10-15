<?php

namespace Spork\Reminders\Jobs;

use Spork\Reminders\Events\ReminderTriggered;
use Spork\Core\Models\FeatureList;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Spork\Reminders\Contracts\ReminderRepositoryContract;

class ProcessRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ReminderRepositoryContract $repository)
    {
        /*
         * Ideally, this repository method should return every reminder that repeats for a user that started before this moment.
         * It should also include any reminder that falls in between the last minute.
         * Then the only extra logic we have to execute is based on the repeating job. Not the non-repeating job.
         */
        $reminders = $repository->findAllReadyToRemind();

        foreach ($reminders as $reminder) {
            if ($this->isReadyToRun($reminder)) {
                info($reminder->name, $reminder->toArray());
                event(new ReminderTriggered($reminder));
            }
        }
    }

    protected function isReadyToRun(FeatureList $reminder): bool
    {
        if (!$reminder->should_repeat) {
            // This method will only get reminders that should be executed.
            return $this->isReminderReadyToRun($reminder);
        }

        return $this->isRepeatingReminderReadyToRun($reminder);
    }

    protected function isRepeatingReminderReadyToRun(FeatureList $reminder)
    {
        /**
         * @var Carbon $lastOccurrence
         * @var Carbon $nextOccurrence
         */
        $lastOccurrence = $reminder->last_occurrence;
        $nextOccurrence = Arr::first($reminder->next_twelve_occurrences);

        if (empty($lastOccurrence)) {
            return false;
        }

        return now()->format('Y-m-d H:i') == $lastOccurrence->format('Y-m-d H:i');
    }

    protected function isReminderReadyToRun(FeatureList $reminder)
    {
        return $reminder->remind_at->format('Y-m-d H:i') === now()->format('Y-m-d H:i');
    }
}
