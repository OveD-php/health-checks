# Health Checks
[![Build Status](https://travis-ci.org/phpsafari/health-checks.svg?branch=master)](https://travis-ci.org/phpsafari/health-checks)

Add health checks to your Laravel applications with this package.

## Install

First, use Composer to install this package.
```bash
$ composer require phpsafari/health-checks
```

After the package has been installed, go ahead and add ``Vistik\ServiceProvider\HealthCheckServiceProvider::class` to your `config/app.php` file.

After doing this, you can publish the `health.php` configuration file into your `config` folder by running:

```bash
$ php artisan vendor:publish --tag=health
```

## Usage

Inside the `config/health.php` file is where you configure all your available health checks. By default, this package comes packed with a few checks already:

* `CorrectEnvironment` will check if your application's environment is set to `production`
* `DatabaseOnline` will check your database connection
* `DebugModeOff` will check if debug mode is off
* `QueueProcessing` will check if the queue is running and jobs are getting processed
* `PathIsWritable` will check if a provided path is writable
* `HasUnrunMigrations` will check if your application has any migrations that hasn't been migrated yet

To run a health check of your application, run:

```bash
$ php artisan health:check
```

You can also use Laravel's scheduler to schedule your health checks:

```php
$schedule->command('health:check')->hourly();
```

### Url based health checks

You can also run a health check by hitting the `https://<APP_URL>/_health` url in a browser or with a tool like Pingdom.

**Note:** _This feature can be disabled in the `config/health.php` file, by setting `route.enabled` to `false`.

### Creating your own health checks

In order to create your own health checks, you just need to extend the `Vistik\Checks\HealthCheck` class and implement the `run()` method.

**Example:**

```php
class IsFriday extends Vistik\Checks\HealthCheck
{
    public function run(): bool
    {
        return Carbon::now()->isFriday();
    }
}
```

Then add `new IsFriday()` to the list of checks in `config/health.php`.


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ phpunit tests/
```

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [Visti Kløft](https://github.com/vistik)
- [Peter Suhm](https://github.com/petersuhm)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
