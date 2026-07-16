<?php

namespace LaravelDoctor\Checks;

use Illuminate\Contracts\Foundation\Application;
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;

final readonly class ConfigCacheCheck implements Check
{
    public function __construct(private Application $app) {}

    public function run(): CheckResult
    {
        return $this->app->configurationIsCached()
            ? CheckResult::pass('Config cache', 'Configuration is cached.')
            : CheckResult::warning('Config cache', 'Configuration is not cached.');
    }
}
