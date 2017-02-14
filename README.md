[![Build Status](https://travis-ci.org/vistik/heath-checks.svg?branch=master)](https://travis-ci.org/vistik/heath-checks)

#### What is this?

This is a simple laravel package to add health checks to your Laravel application

#### Installation & setup

Run `composer require vistik/health-checks`

add `Vistik\ServiceProvider\HealthCheckServiceProvider::class,` to your `app.php` file

run `php artisan vendor:publish --tag=health`

goto `config/health.php` and checkout potential health-checks

By default the following checks are setup:
- `CorrectEnvironment` will check if `app.env` is set to production
- `DatabaseOnline` will check if we can get a connection to the Database
- `DebugModeOff` will check if debug mode is off
- `QueueProcessing` will check if the queue is running and jobs are getting processed

run `php artisan health:check` from your commandline

or

goto `[GET] http://<APP_URL>/_health` to run your checks you could setup something like pingdom.com to check the health of your application
You can disable this route by setting `route.enabled = false` in `config/health.php`

You could also to laravels build in command scheduling and add:
```php
    $schedule->command('health:check')->hourly();
```

in `App\Console\Kernel@schedule`

#### Create your own checks

Create a new class `IsFriday` and extend `Vistik\Checks\HealthCheck`
In `public function run(): bool` Do your check and return `true` or `false`
In this case 
```php
public function run(): bool{
    return Carbon::now()->isFriday();
}
```

Then goto `config/health.php` and add IsFriday() to the list of checks