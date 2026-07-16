<?php

use LaravelDoctor\Checks\AppDebugCheck;
use LaravelDoctor\Checks\CacheStoreCheck;
use LaravelDoctor\Checks\QueueConnectionCheck;
use LaravelDoctor\Enums\CheckStatus;

it('fails unsafe production settings', function () {
    config()->set([
        'app.env' => 'production',
        'app.debug' => true,
        'queue.default' => 'sync',
        'cache.default' => 'array',
    ]);

    expect(app(AppDebugCheck::class)->run()->status)->toBe(CheckStatus::Fail)
        ->and(app(QueueConnectionCheck::class)->run()->status)->toBe(CheckStatus::Fail)
        ->and(app(CacheStoreCheck::class)->run()->status)->toBe(CheckStatus::Fail);
});

it('allows development queue and cache defaults', function () {
    config()->set([
        'app.env' => 'local',
        'queue.default' => 'sync',
        'cache.default' => 'array',
    ]);

    expect(app(QueueConnectionCheck::class)->run()->status)->toBe(CheckStatus::Pass)
        ->and(app(CacheStoreCheck::class)->run()->status)->toBe(CheckStatus::Pass);
});
