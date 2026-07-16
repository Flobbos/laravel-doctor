<?php

namespace LaravelDoctor\Contracts;

use LaravelDoctor\ValueObjects\CheckResult;

interface Check
{
    public function run(): CheckResult;
}
