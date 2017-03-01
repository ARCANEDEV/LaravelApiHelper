<?php namespace Arcanedev\LaravelApiHelper;

use Arcanedev\Support\PackageServiceProvider;

/**
 * Class     ApiHelperServiceProvider
 *
 * @package  Arcanedev\LaravelApiHelper
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ApiHelperServiceProvider extends PackageServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */
    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'api-helper';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

        $this->registerConfig();

        $this->singleton(Contracts\Http\JsonResponse::class, Http\JsonResponse::class);
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

        $this->registerProvider(Providers\RouteServiceProvider::class);

        $this->publishConfig();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Contracts\Http\JsonResponse::class,
        ];
    }
}
