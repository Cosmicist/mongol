<?php namespace Flatline\Mongol;

use Illuminate\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Flatline\Mongol\Facades\Mongol;
use Flatline\Mongol\Auth\MongolUserProvider;

class MongolServiceProvider extends ServiceProvider {

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
        $this->package('flatline/mongol');

        // Register the mongol Auth driver
        Auth::extend('mongol', function() {
            $table = $this->app['config']['auth.table'];
            $provider = new MongolUserProvider(Mongol::connection(), $this->app['hash'], $table);
            return new Guard($provider, $this->app['session']);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['mongol'] = $this->app->share(function($app) {
            return new MongolManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('mongol');
    }
}