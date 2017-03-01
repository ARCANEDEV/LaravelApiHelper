<?php namespace Arcanedev\LaravelApiHelper\Contracts;

/**
 * Interface  Transformable
 *
 * @package   Arcanedev\LaravelApiHelper\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Transformable
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Transform the instance.
     *
     * @param  callable  $callable
     *
     * @return \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable
     */
    public function transform(callable $callable);
}
