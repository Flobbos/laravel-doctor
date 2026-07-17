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

it('applies check weights to the score', function () {
    $report = new DoctorReport(new Collection([
        CheckResult::pass('Pass', 'Passed.')->withWeight(3),
        CheckResult::fail('Fail', 'Failed.'),
    ]));

    expect($report->score())->toBe(75);
});

it('serializes its results', function () {
    $report = new DoctorReport(new Collection([
        CheckResult::warning('Cache', 'Not cached.')->withWeight(2),
    ]));

    expect($report->toArray())->toBe([
        'score' => 50,
        'results' => [[
            'name' => 'Cache',
            'status' => 'warning',
            'message' => 'Not cached.',
            'weight' => 2,
        ]],
    ]);
});
