<?php

use LaravelDoctor\Checks\AppDebugCheck;
use LaravelDoctor\Checks\AppEnvironmentCheck;
use LaravelDoctor\Checks\CacheStoreCheck;
use LaravelDoctor\Checks\ConfigCacheCheck;
use LaravelDoctor\Checks\DatabaseConnectionCheck;
use LaravelDoctor\Checks\OpcacheCheck;
use LaravelDoctor\Checks\PendingMigrationsCheck;
use LaravelDoctor\Checks\QueueConnectionCheck;
use LaravelDoctor\Checks\RouteCacheCheck;

return [
    /*
    | Add your own classes here. Each class must implement Check.
    */
    'checks' => [
        AppDebugCheck::class,
        AppEnvironmentCheck::class,
        QueueConnectionCheck::class,
        CacheStoreCheck::class,
        ConfigCacheCheck::class,
        RouteCacheCheck::class,
        OpcacheCheck::class,
        DatabaseConnectionCheck::class,
        PendingMigrationsCheck::class,
    ],

    /*
    | More important checks can contribute more heavily to the score.
    */
    'weights' => [
        AppDebugCheck::class => 2,
        AppEnvironmentCheck::class => 1,
        QueueConnectionCheck::class => 1,
        CacheStoreCheck::class => 1,
        ConfigCacheCheck::class => 1,
        RouteCacheCheck::class => 1,
        OpcacheCheck::class => 1,
        DatabaseConnectionCheck::class => 2,
        PendingMigrationsCheck::class => 2,
    ],
];
