<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers;

use Arcanedev\LaravelApiHelper\Transformer;

/**
 * Class     FluentTransformer
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FluentTransformer extends Transformer
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Transform the given resource for the API output.
     *
     * @param  \Illuminate\Support\Fluent  $resource
     *
     * @return array
     */
    public function transformResource($resource)
    {
        return [
            'title'   => $resource->get('title'),
            'content' => $resource->get('content'),
        ];
    }
}
