<?php

namespace LaravelDoctor\Checks;

use Illuminate\Contracts\Config\Repository;
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;

final readonly class AppEnvironmentCheck implements Check
{
    public function __construct(private Repository $config) {}

    public function run(): CheckResult
    {
        $environment = trim((string) $this->config->get('app.env'));

        return $environment === ''
            ? CheckResult::fail('APP_ENV', 'Not configured.')
            : CheckResult::pass('APP_ENV', "Set to {$environment}.");
    }
}
