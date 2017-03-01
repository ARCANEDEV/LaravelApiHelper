<?php namespace Arcanedev\LaravelApiHelper\Traits;

use Illuminate\Support\Fluent;

/**
 * Class     TransformableModel
 *
 * @package  Arcanedev\LaravelApiHelper\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait TransformableModel
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Transform the model.
     *
     * @param  callable  $callable
     *
     * @return \Illuminate\Support\Fluent
     */
    public function transform(callable $callable)
    {
        return new Fluent($callable($this));
    }
}
