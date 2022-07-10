<?php

namespace Spork\Reminders;

use App\Spork;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class ReminderServiceProvider extends RouteServiceProvider
{
    public function boot()
    {
      //
    }
    
    public function register()
    {
        Spork::addFeature('Reminders', 'AnnotationIcon', '/reminders', 'crud');
      
        if (config('spork.reminders.enabled')) {
            Route::middleware($this->app->make('config')->get('spork.reminders.middleware', ['auth:sanctum']))
                ->prefix('api/reminders')
                ->group(__DIR__ . '/../routes/web.php');
        }
    }
}
