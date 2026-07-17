<?php

namespace LaravelDoctor;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\DoctorReport;

final readonly class DoctorRunner
{
    public function __construct(private Container $container) {}

    /**
     * @param  array<int, class-string<Check>>  $checks
     * @param  array<class-string<Check>, int>  $weights
     */
    public function run(array $checks, array $weights = []): DoctorReport
    {
        $results = Collection::make($checks)->map(function (string $check) use ($weights) {
            $instance = $this->container->make($check);

            if (! $instance instanceof Check) {
                throw new InvalidArgumentException("Doctor check [{$check}] must implement ".Check::class.'.');
            }

            $weight = $weights[$check] ?? 1;

            if (! is_int($weight) || $weight < 1) {
                throw new InvalidArgumentException("Doctor check weight [{$check}] must be a positive integer.");
            }

            return $instance->run()->withWeight($weight);
        });

        return new DoctorReport($results);
    }
}
