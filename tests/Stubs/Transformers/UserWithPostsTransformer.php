<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers;

use Arcanedev\LaravelApiHelper\Transformer;

/**
 * Class     UserWithPostsTransformer
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UserWithPostsTransformer extends Transformer
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Transform the given resource for the API output.
     *
     * @param  \Arcanedev\LaravelApiHelper\Tests\Stubs\Models\User  $resource
     *
     * @return array
     */
    public function transformResource($resource)
    {
        $posts = $resource->posts;

        $this->withMeta([
            'posts_count' => $posts->count(),
        ]);

        return [
            'full_name' => $resource->full_name,
            'email'     => $resource->email,
            'posts'     => PostTransformer::subResource($posts),
        ];
    }
}
