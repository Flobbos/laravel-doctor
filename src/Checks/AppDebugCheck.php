<?php

namespace LaravelDoctor\Checks;

use Illuminate\Contracts\Config\Repository;
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;

final readonly class AppDebugCheck implements Check
{
    public function __construct(private Repository $config) {}

    public function run(): CheckResult
    {
        $enabled = (bool) $this->config->get('app.debug');

        if ($this->config->get('app.env') === 'production' && $enabled) {
            return CheckResult::fail('APP_DEBUG', 'Enabled in production.');
        }

        return $enabled
            ? CheckResult::warning('APP_DEBUG', 'Enabled outside production.')
            : CheckResult::pass('APP_DEBUG', 'Disabled.');
    }
}
