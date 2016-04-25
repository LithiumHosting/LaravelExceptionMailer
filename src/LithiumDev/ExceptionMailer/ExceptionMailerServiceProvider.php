<?php

namespace LithiumDev\ExceptionMailer;


use Illuminate\Support\ServiceProvider;

class ExceptionMailerServiceProvider extends ServiceProvider {
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('laravel-exception-mailer/config.php'),
            __DIR__ . '/../../views' => base_path('resources/views/vendor/laravel-exception-mailer'),
            ]);
        $this->loadViewsFrom(__DIR__ . '/../../views', 'laravel-exception-mailer');

        $this->app->singleton('Illuminate\Contracts\Debug\ExceptionHandler', 'LithiumDev\ExceptionMailer\ExceptionHandler');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php', 'laravel-exception-mailer.config'
        );

        $this->app['ExceptionMailer'] = $this->app->share(function ($app)
        {
            $config  = $app['config']['laravel-exception-mailer']['config'];
            $eMailer = new ExceptionMailer($config);

            if (in_array($app->environment(), $config['notify_environment']))
            {
                $eMailer->setEnvironment($app->environment());
            }

            return $eMailer;
        });
        $this->app->singleton('ExceptionMailer',
            function ($app)
            {
                $config = $app['config']['laravel-exception-mailer']['config'];

                $eMailer = new ExceptionMailer($config);

                if (in_array($app->environment(), $config['notify_environment']))
                {
                    $eMailer->setEnvironment($app->environment());
                }

                return $eMailer;
            });
        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function ()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('ExceptionMailer', 'LithiumDev\ExceptionMailer\Facades\ExceptionMailerFacade');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array("ExceptionMailer");
    }
}