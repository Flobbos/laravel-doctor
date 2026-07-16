<?php

namespace LaravelDoctor\Commands;

use Illuminate\Console\Command;
use LaravelDoctor\DoctorRunner;
use LaravelDoctor\Enums\CheckStatus;

final class DoctorCommand extends Command
{
    protected $signature = 'doctor';

    protected $description = 'Run Laravel application health checks';

    public function handle(DoctorRunner $runner): int
    {
        $report = $runner->run(config('doctor.checks', []));

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

        return self::SUCCESS;
    }
}
