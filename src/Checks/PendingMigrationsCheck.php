<?php

namespace LaravelDoctor\Checks;

use Illuminate\Database\Migrations\Migrator;
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;
use Throwable;

final readonly class PendingMigrationsCheck implements Check
{
    public function __construct(private Migrator $migrator) {}

    public function run(): CheckResult
    {
        try {
            if (! $this->migrator->repositoryExists()) {
                return CheckResult::warning('Migrations', 'Migration table does not exist.');
            }

            $paths = array_unique([
                database_path('migrations'),
                ...$this->migrator->paths(),
            ]);
            $files = $this->migrator->getMigrationFiles($paths);
            $pending = array_diff(array_keys($files), $this->migrator->getRepository()->getRan());

            return $pending === []
                ? CheckResult::pass('Migrations', 'No pending migrations.')
                : CheckResult::fail('Migrations', count($pending).' pending migration(s).');
        } catch (Throwable $exception) {
            return CheckResult::fail('Migrations', 'Could not inspect migrations: '.$exception->getMessage());
        }
    }
}
