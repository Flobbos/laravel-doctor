<?php

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;

it('renders a report and score', function () {
    config()->set('doctor.checks', [PassingCheck::class, FailingCheck::class]);

    $this->artisan('doctor')
        ->expectsOutputToContain('Laravel Doctor')
        ->expectsOutputToContain('Healthy')
        ->expectsOutputToContain('Broken')
        ->expectsOutputToContain('50/100')
        ->assertSuccessful();
});

it('runs custom checks registered in configuration', function () {
    config()->set('doctor.checks', [PassingCheck::class]);

    $this->artisan('doctor')
        ->expectsOutputToContain('Healthy')
        ->expectsOutputToContain('100/100')
        ->assertSuccessful();
});

final class PassingCheck implements Check
{
    public function run(): CheckResult
    {
        return CheckResult::pass('Healthy', 'Everything is fine.');
    }
}

final class FailingCheck implements Check
{
    public function run(): CheckResult
    {
        return CheckResult::fail('Broken', 'Needs attention.');
    }
}
