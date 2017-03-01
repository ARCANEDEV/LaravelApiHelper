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
        $router->group(['middleware' => 'ajax'], function () use ($router) {
            $router->get('/', function () {
                return response()->json([
                    'status' => 'success',
                    'code'   => 200,
                    'data'   => 'Hello world'
                ], 200);
            });
        });
    }
}
