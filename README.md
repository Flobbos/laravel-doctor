# Laravel Doctor

A small, zero-dependency health report for Laravel applications.

![Laravel Doctor terminal report](docs/terminal.png)

```text
Laravel Doctor

  ✓ APP_DEBUG    Disabled.
  ✓ APP_ENV      Set to production.
  ✓ Queue        Using the redis connection.
  ✓ Cache        Using the redis store.
  ✓ Config cache Configuration is cached.
  ! Route cache  Routes are not cached.
  ✓ OPcache      Enabled.
  ✓ Database     Connected via mysql.
  ✓ Migrations   No pending migrations.

  Score  96/100
```

## Requirements

- PHP 8.2+
- Laravel 11, 12, or 13

## Installation

```bash
composer require flobbos/laravel-doctor --dev
```

Laravel discovers the service provider automatically. Run the report with:

```bash
php artisan doctor
```

Use it in CI or deployments:

```bash
php artisan doctor --ci
php artisan doctor --minimum=80
php artisan doctor --json
```

`--ci` returns a non-zero exit code when a check fails. `--minimum` enforces a score from 0 to 100. `--json` returns the same report in a machine-readable format.

Publish the configuration when you want to customize the checks:

```bash
php artisan vendor:publish --tag=doctor-config
```

## Custom checks

Implement `LaravelDoctor\Contracts\Check` and add the class to `config/doctor.php`:

```php
use LaravelDoctor\Contracts\Check;
use LaravelDoctor\ValueObjects\CheckResult;

final class DatabaseCheck implements Check
{
    public function run(): CheckResult
    {
        return CheckResult::pass('Database', 'Connection is healthy.');
    }
}
```

The container resolves every check, so constructor injection works as expected. Results can be `pass`, `warning`, or `fail`. Passes earn full score, warnings half score, and failures no score.

## Scoring weights

Published configuration includes a `weights` array keyed by check class. Increase a value when that check should contribute more heavily to the score:

```php
'weights' => [
    AppDebugCheck::class => 2,
    DatabaseConnectionCheck::class => 3,
],
```

## Development

```bash
composer install
composer test
composer format
```

## License

MIT
