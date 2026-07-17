<?php

use Illuminate\Support\Facades\Artisan;
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

it('returns machine readable json', function () {
    config()->set('doctor.checks', [PassingCheck::class]);

    expect(Artisan::call('doctor', ['--json' => true]))->toBe(0);

    $output = json_decode(Artisan::output(), true);

    expect($output['score'])->toBe(100)
        ->and($output['results'][0]['status'])->toBe('pass');
});

it('fails in ci mode when a check fails', function () {
    config()->set('doctor.checks', [FailingCheck::class]);

    $this->artisan('doctor --ci')->assertFailed();
});

it('fails below the requested minimum score', function () {
    config()->set('doctor.checks', [PassingCheck::class, FailingCheck::class]);

    $this->artisan('doctor --minimum=80')->assertFailed();
});

it('rejects an invalid minimum score', function () {
    $this->artisan('doctor --minimum=101')
        ->expectsOutputToContain('between 0 and 100')
        ->assertExitCode(2);
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
