<?php namespace Arcanedev\LaravelApiHelper\Tests;

use Orchestra\Testbench\BrowserKit\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelApiHelper\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\LaravelApiHelper\ApiHelperServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'JsonResponse' => \Arcanedev\LaravelApiHelper\Facades\JsonResponse::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->registerRoutes($app['router']);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    /**
     * Register the routes for tests
     *
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     */
    private function registerRoutes($router)
    {
        $router->namespace('Arcanedev\\LaravelApiHelper\\Tests\\Stubs\\Controllers')
               ->middleware('ajax')
               ->name('api::')
               ->group(function () use ($router) {
                   $router->get('/', 'ApiController@index')
                          ->name('index'); // api::index

                   $router->get('{slug}', 'ApiController@show')
                          ->name('show');  // api::show
               });
    }

    /**
     * Call (AJAX) the given URI and return the Json Response.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array   $parameters
     * @param  array   $cookies
     * @param  array   $files
     * @param  array   $server
     * @param  string  $content
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function ajaxCall($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $server = array_merge(['HTTP_X-Requested-With' => 'XMLHttpRequest'], $server);

        return $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }
}
