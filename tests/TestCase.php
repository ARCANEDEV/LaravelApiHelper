<?php namespace Arcanedev\LaravelApiHelper\Tests;

use Illuminate\Support\Facades\Schema;
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
        $app['config']->set('app.debug', true);

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

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

                   $router->post('form-request', 'ApiController@form')
                          ->name('form-request');  // api::form-request
               });
    }

    protected function createTables()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
        });

        Schema::create('posts', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title');
            $table->text('content');
        });
    }
}
