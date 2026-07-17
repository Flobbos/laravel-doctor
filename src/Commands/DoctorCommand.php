<?php

namespace LaravelDoctor\Commands;

use Illuminate\Console\Command;
use LaravelDoctor\DoctorRunner;
use LaravelDoctor\Enums\CheckStatus;

final class DoctorCommand extends Command
{
    protected $signature = 'doctor
        {--ci : Exit with a failure code when a check fails}
        {--minimum= : Exit with a failure code when the score is below this value}
        {--json : Output the report as JSON}';

    protected $description = 'Run Laravel application health checks';

    public function handle(DoctorRunner $runner): int
    {
        $minimum = $this->minimumScore();

        if ($minimum === false) {
            return self::INVALID;
        }

        $report = $runner->run(
            config('doctor.checks', []),
            config('doctor.weights', []),
        );

        if ($this->option('json')) {
            $this->line(json_encode($report->toArray(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));

            return $this->exitCode($report->hasFailures(), $report->score(), $minimum);
        }

        $this->newLine();
        $this->components->info('Laravel Doctor');
        $this->newLine();

        foreach ($report->results as $result) {
            $icon = match ($result->status) {
                CheckStatus::Pass => '<fg=green>✓</>',
                CheckStatus::Warning => '<fg=yellow>!</>',
                CheckStatus::Fail => '<fg=red>✗</>',
            };

            $this->line("  {$icon} <options=bold>{$result->name}</>  {$result->message}");
        }

        $this->newLine();
        $score = $report->score();
        $color = $score >= 80 ? 'green' : ($score >= 50 ? 'yellow' : 'red');
        $this->line("  Score  <fg={$color};options=bold>{$score}/100</>");
        $this->newLine();

        return $this->exitCode($report->hasFailures(), $score, $minimum);
    }

    private function minimumScore(): int|false|null
    {
        $minimum = $this->option('minimum');

        if ($minimum === null) {
            return null;
        }

        if (filter_var($minimum, FILTER_VALIDATE_INT) === false || (int) $minimum < 0 || (int) $minimum > 100) {
            $this->components->error('The minimum score must be an integer between 0 and 100.');

            return false;
        }

        return (int) $minimum;
    }

    private function exitCode(bool $hasFailures, int $score, ?int $minimum): int
    {
        if (($this->option('ci') && $hasFailures) || ($minimum !== null && $score < $minimum)) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
