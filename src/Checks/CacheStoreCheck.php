<?php

namespace LaravelDoctor\Checks;

use Illuminate\Contracts\Config\Repository;
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;

final readonly class CacheStoreCheck implements Check
{
    public function __construct(private Repository $config) {}

    public function run(): CheckResult
    {
        $store = (string) $this->config->get('cache.default', 'array');

        if ($this->config->get('app.env') === 'production' && $store === 'array') {
            return CheckResult::fail('Cache', 'Using the array store in production.');
        }

        return CheckResult::pass('Cache', "Using the {$store} store.");
    }
}
