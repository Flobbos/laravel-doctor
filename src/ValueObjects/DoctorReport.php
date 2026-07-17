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

        $points = $this->results->sum(fn (CheckResult $result): float => $result->weight * match ($result->status) {
            CheckStatus::Pass => 1,
            CheckStatus::Warning => 0.5,
            CheckStatus::Fail => 0,
        });

        $maximum = $this->results->sum(fn (CheckResult $result): int => $result->weight);

        return (int) round(($points / $maximum) * 100);
    }

    public function hasFailures(): bool
    {
        return $this->results->contains(
            fn (CheckResult $result): bool => $result->status === CheckStatus::Fail
        );
    }

    /** @return array{score: int, results: array<int, array{name: string, status: string, message: string, weight: int}>} */
    public function toArray(): array
    {
        return [
            'score' => $this->score(),
            'results' => $this->results->map(fn (CheckResult $result): array => [
                'name' => $result->name,
                'status' => $result->status->value,
                'message' => $result->message,
                'weight' => $result->weight,
            ])->all(),
        ];
    }
}
