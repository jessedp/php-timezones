<?php
namespace jessedp\Timezones;


use Illuminate\Support\ServiceProvider;

/**
 * TimezonesServiceProvider
 *
 * @package jessedp\Timezones
 * @author jessep <jessedp@gmail.com>
 */
class TimezonesServiceProvider extends ServiceProvider
{
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
        
        // $this->app->singleton('timezones', function ($app) {
        //     return new Timezones;
        // });

        $this->app->bind(Timezones::class,  function () {
            return new Timezones;
            // $loader = AliasLoader::getInstance();
            // $loader->alias('Timezones', 'jessedp\Timezones\Facades\Timezones');
        });

        $this->app->alias(Timezones::class, 'timezones');

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
