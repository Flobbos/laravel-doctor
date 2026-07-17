<?php

namespace LaravelDoctor\Checks;

use Illuminate\Database\DatabaseManager;
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;
use Throwable;

final readonly class DatabaseConnectionCheck implements Check
{
    public function __construct(private DatabaseManager $database) {}

    public function run(): CheckResult
    {
        try {
            $connection = $this->database->connection();
            $connection->getPdo();

            return CheckResult::pass('Database', "Connected via {$connection->getName()}.");
        } catch (Throwable $exception) {
            return CheckResult::fail('Database', 'Connection failed: '.$exception->getMessage());
        }
    }
}
