<?php namespace Arcanedev\LaravelApiHelper\Providers;

use Arcanedev\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class     RouteServiceProvider
 *
 * @package  Arcanedev\LaravelApiHelper\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Define the routes for the application.
     */
    public function map()
    {
        $this->registerApiMiddleware();
    }

    /**
     * Register the API route Middleware.
     */
    private function registerApiMiddleware()
    {
        /** @var  \Illuminate\Contracts\Config\Repository  $config */
        $config = $this->app['config'];

        foreach ($config->get('api-helper.middleware', []) as $name => $class) {
            $this->aliasMiddleware($name, $class);
        };
    }
}
