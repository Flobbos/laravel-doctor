<?php

namespace LaravelDoctor\ValueObjects;

use Illuminate\Support\Collection;
use LaravelDoctor\Enums\CheckStatus;

final readonly class DoctorReport
{
    /** @param Collection<int, CheckResult> $results */
    public function __construct(public Collection $results) {}

    public function score(): int
    {
        if ($this->results->isEmpty()) {
            return 100;
        }

        $points = $this->results->sum(fn (CheckResult $result): float => match ($result->status) {
            CheckStatus::Pass => 1,
            CheckStatus::Warning => 0.5,
            CheckStatus::Fail => 0,
        });

        return (int) round(($points / $this->results->count()) * 100);
    }
}
