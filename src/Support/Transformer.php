<?php namespace Arcanedev\LaravelApiHelper\Support;

use Arcanedev\LaravelApiHelper\Contracts\Transformable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Fluent;

/**
 * Class     Transformer
 *
 * @package  Arcanedev\LaravelApiHelper\Support
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Transformer
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Make the transformation.
     *
     * @param  mixed  $resource
     *
     * @return mixed
     */
    public static function make($resource)
    {
        return (new static)->handle($resource);
    }

    /**
     * Handle the transformation.
     *
     * @param  mixed  $resource
     *
     * @return mixed
     */
    public function handle($resource)
    {
        $this->checkTransformMethod();

        if ($resource instanceof Paginator)
            $resource = $resource->getCollection();

        if (
            $resource instanceof Transformable ||
            $resource instanceof Fluent
        )
            return $this->transform($resource);

        return $resource->transform($this);
    }

    /**
     * Invoke the transformer.
     *
     * @param  mixed  $resource
     *
     * @return mixed
     */
    public function __invoke($resource)
    {
        return $this->handle($resource);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    /**
     * Check if the `transform` method exists.
     */
    protected function checkTransformMethod()
    {
        if ( ! method_exists($this, 'transform')) {
            $class = get_class($this);

            throw new \BadMethodCallException("Missing [transform] method in {$class}.");
        }
    }
}
