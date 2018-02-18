<?php namespace jessedp\Timezones;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * TimezonelistServiceProvider
 *
 * @package jessedp\Timezones
 * @author jessep <jessedp@gmail.com>
 */
class TimezonesServiceProvider extends ServiceProvider
{
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
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('timezones', function ($app) {
            return new Timezones;
        });

        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Timezones', 'jessedp\Timeszones\Facades\Timezones');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['timezones'];
    }
}
