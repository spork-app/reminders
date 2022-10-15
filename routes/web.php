<?php

use Spork\Core\Models\FeatureList;
use Illuminate\Support\Facades\Route;

// Route::get('/', 'Controller@method');

Route::post('events', function () {
    $feature = FeatureList::where('feature', 'reminders')->firstOrFail();

    $event = $feature->repeatable()->create(array_filter([
        'name' => request()->get('name', null),
        'color' => request()->get('color', null),
        'interval' => request()->get('interval', null),
        'frequency' => request()->get('frequency', null),
        'weekday_start' => request()->get('weekday_start', null),
        'number_of_occurrences' => request()->get('number_of_occurrences', null),
        'date_start' => request()->get('date_start', null),
        'date_end' => request()->get('date_end', null),
        'for_months' => request()->get('for_months', null),
        'for_week_numbers' => request()->get('for_week_numbers', null),
        'for_year_day' => request()->get('for_year_day', null),
        'for_month_day' => request()->get('for_month_day', null),
        'for_day' => request()->get('for_day', null),
        'for_hour' => request()->get('for_hour', null),
        'for_minute' => request()->get('for_minute', null),
        'for_second' => request()->get('for_second', null),
        'for_set_position' => request()->get('for_set_position', null),
        'user_id' => auth()->id(),
        'feature_list_id' => $feature->id,
    ]));

    return $event;
});

Route::delete('events/{event}', function ($event) {
    $feature = FeatureList::where('feature', 'reminders')->firstOrFail();

    $event = $feature->repeatable()->findOrFail($event);

    $event->delete();
    return '';
});