<?php

namespace LaravelDoctor\Checks;

use Illuminate\Contracts\Config\Repository;
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;

final readonly class QueueConnectionCheck implements Check
{
    public function __construct(private Repository $config) {}

    public function run(): CheckResult
    {
        $connection = (string) $this->config->get('queue.default', 'sync');

        if ($this->config->get('app.env') === 'production' && $connection === 'sync') {
            return CheckResult::fail('Queue', 'Using the sync connection in production.');
        }

        return CheckResult::pass('Queue', "Using the {$connection} connection.");
    }
}
