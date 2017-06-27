<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers;

use Arcanedev\LaravelApiHelper\Transformer;

/**
 * Class     BasicTransformer
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class BasicTransformer extends Transformer
{
    /* -----------------------------------------------------------------
     |  Main Method
     | -----------------------------------------------------------------
     */

    /**
     * Transform the given resource for the API output.
     *
     * @param  array  $resource
     *
     * @return array
     */
    public function transformResource($resource)
    {
        return $resource;
    }
}
