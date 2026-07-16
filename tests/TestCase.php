<?php

namespace LaravelDoctor\Tests;

use LaravelDoctor\DoctorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [DoctorServiceProvider::class];
    }
}
