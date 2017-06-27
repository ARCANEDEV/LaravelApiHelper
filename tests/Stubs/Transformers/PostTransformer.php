<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers;

use Arcanedev\LaravelApiHelper\Transformer;
use Arcanedev\LaravelApiHelper\Tests\Stubs\Models\Post;
use Illuminate\Support\Str;

/**
 * Class     PostTransformer
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Stubs\Models\Transformers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PostTransformer extends Transformer
{
    /* -----------------------------------------------------------------
     |  Main Method
     | -----------------------------------------------------------------
     */

    /**
     * Transform the instance.
     *
     * @param  \Arcanedev\LaravelApiHelper\Tests\Stubs\Models\Post  $resource
     *
     * @return array
     */
    public function transformResource($resource)
    {
        return [
            'title'   => $resource->title,
            'slug'    => Str::slug($resource->title),
            'content' => $resource->content,
        ];
    }
}
