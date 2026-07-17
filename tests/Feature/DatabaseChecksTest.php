<?php

use LaravelDoctor\Checks\DatabaseConnectionCheck;
use LaravelDoctor\Checks\PendingMigrationsCheck;
use LaravelDoctor\Enums\CheckStatus;

it('checks the default database connection', function () {
    expect(app(DatabaseConnectionCheck::class)->run()->status)->toBe(CheckStatus::Pass);
});

it('warns when the migration repository does not exist', function () {
    expect(app(PendingMigrationsCheck::class)->run()->status)->toBe(CheckStatus::Warning);
});
