<?php

namespace LaravelDoctor;

use Illuminate\Support\ServiceProvider;
use LaravelDoctor\Commands\DoctorCommand;

final class DoctorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/doctor.php', 'doctor');
        $this->app->singleton(DoctorRunner::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/doctor.php' => config_path('doctor.php'),
        ], 'doctor-config');

        if ($this->app->runningInConsole()) {
            $this->commands(DoctorCommand::class);
        }
    }
}
