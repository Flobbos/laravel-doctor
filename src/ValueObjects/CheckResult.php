<?php

namespace LaravelDoctor\ValueObjects;

use LaravelDoctor\Enums\CheckStatus;

final readonly class CheckResult
{
    public function __construct(
        public string $name,
        public CheckStatus $status,
        public string $message,
    ) {}

    public static function pass(string $name, string $message): self
    {
        return new self($name, CheckStatus::Pass, $message);
    }

    public static function fail(string $name, string $message): self
    {
        return new self($name, CheckStatus::Fail, $message);
    }

    public static function warning(string $name, string $message): self
    {
        return new self($name, CheckStatus::Warning, $message);
    }
}
