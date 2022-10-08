<?php

namespace Spork\Reminders;

use Spork\Core\Spork;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Spork\Reminders\Contracts\ReminderRepositoryContract;
use Spork\Reminders\Repositories\ReminderRepository;

class ReminderServiceProvider extends RouteServiceProvider
{
    public function boot()
    {
      //
    }
    
    public function register()
    {
        $this->app->bind(ReminderRepositoryContract::class, ReminderRepository::class);
        
        Spork::addFeature('Reminders', 'AnnotationIcon', '/reminders', 'crud');
      
        if (config('spork.reminders.enabled')) {
            Route::middleware($this->app->make('config')->get('spork.reminders.middleware', ['auth:sanctum']))
                ->prefix('api/reminders')
                ->group(__DIR__ . '/../routes/web.php');
        }
    }
}
