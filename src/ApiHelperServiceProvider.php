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
    public function register()
    {
        parent::register();

        $this->registerConfig();
    }

    public function boot()
    {
        parent::boot();

        $this->registerProvider(Providers\RouteServiceProvider::class);

        $this->publishConfig();
    }

    public function provides()
    {
        return [
            //
        ];
    }
}
