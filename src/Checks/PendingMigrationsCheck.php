<?php

namespace LaravelDoctor\Checks;

use Illuminate\Contracts\Container\Container;
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;
use Throwable;

final readonly class PendingMigrationsCheck implements Check
{
    public function __construct(private Container $container) {}

    public function run(): CheckResult
    {
        try {
            $migrator = $this->container->make('migrator');

            if (! $migrator->repositoryExists()) {
                return CheckResult::warning('Migrations', 'Migration table does not exist.');
            }

            $paths = array_unique([
                database_path('migrations'),
                ...$migrator->paths(),
            ]);
            $files = $migrator->getMigrationFiles($paths);
            $pending = array_diff(array_keys($files), $migrator->getRepository()->getRan());

            return $pending === []
                ? CheckResult::pass('Migrations', 'No pending migrations.')
                : CheckResult::fail('Migrations', count($pending).' pending migration(s).');
        } catch (Throwable $exception) {
            return CheckResult::fail('Migrations', 'Could not inspect migrations: '.$exception->getMessage());
        }
    }
}
