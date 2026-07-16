<?php

use Illuminate\Support\Collection;
use LaravelDoctor\ValueObjects\CheckResult;
use LaravelDoctor\ValueObjects\DoctorReport;

it('calculates a weighted score', function () {
    $report = new DoctorReport(new Collection([
        CheckResult::pass('Pass', 'Passed.'),
        CheckResult::warning('Warning', 'Warning.'),
        CheckResult::fail('Fail', 'Failed.'),
    ]));

    expect($report->score())->toBe(50);
});

it('gives an empty report a perfect score', function () {
    expect((new DoctorReport(new Collection))->score())->toBe(100);
});
