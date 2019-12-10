<?php

namespace App\Providers;

use Monolog\Handler\SlackHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Slack Notification for Monolog
        if (env('APP_ENV') == 'production') {
            $monolog = \Log::getMonolog();
            $slackHandler = new SlackHandler(env('SLACK_TOKEN'), env('SLACK_CHANNEL'), 'Monolog', true, null, \Monolog\Logger::ERROR);
            $monolog->pushHandler($slackHandler);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
