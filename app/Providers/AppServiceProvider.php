<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('no_http', function($attribute, $value, $parameters, $validator) {
            return !Str::contains($value,'http:');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		/* force HTTPS */
        if (env('APP_ENV') === 'production') {
            $this->app['url']->forceScheme('https');
        }
    }
}
