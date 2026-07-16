<?php

namespace LaravelDoctor\Checks;

use Illuminate\Contracts\Foundation\Application;
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;

final readonly class RouteCacheCheck implements Check
{
    public function __construct(private Application $app) {}

    public function run(): CheckResult
    {
        return $this->app->routesAreCached()
            ? CheckResult::pass('Route cache', 'Routes are cached.')
            : CheckResult::warning('Route cache', 'Routes are not cached.');
    }
}
