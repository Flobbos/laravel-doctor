<?php

namespace LaravelDoctor\Checks;

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;

final class OpcacheCheck implements Check
{
    public function run(): CheckResult
    {
        $enabled = extension_loaded('Zend OPcache') && filter_var(ini_get('opcache.enable'), FILTER_VALIDATE_BOOL);

        return $enabled
            ? CheckResult::pass('OPcache', 'Enabled.')
            : CheckResult::warning('OPcache', 'Disabled.');
    }
}
