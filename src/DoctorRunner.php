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

    /** @param array<int, class-string<Check>> $checks */
    public function run(array $checks): DoctorReport
    {
        $results = Collection::make($checks)->map(function (string $check) {
            $instance = $this->container->make($check);

            if (! $instance instanceof Check) {
                throw new InvalidArgumentException("Doctor check [{$check}] must implement ".Check::class.'.');
            }

            return $instance->run();
        });

        return new DoctorReport($results);
    }
}
