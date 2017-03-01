<?php namespace Arcanedev\LaravelApiHelper\Facades;

use Arcanedev\LaravelApiHelper\Contracts\Http\JsonResponse as JsonResponseContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class     JsonResponse
 *
 * @package  Arcanedev\LaravelApiHelper\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class JsonResponse extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return JsonResponseContract::class; }
}
