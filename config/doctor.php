<?php

use LaravelDoctor\Checks\AppDebugCheck;
use LaravelDoctor\Checks\AppEnvironmentCheck;
use LaravelDoctor\Checks\CacheStoreCheck;
use LaravelDoctor\Checks\ConfigCacheCheck;
use LaravelDoctor\Checks\OpcacheCheck;
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
    ],
];
