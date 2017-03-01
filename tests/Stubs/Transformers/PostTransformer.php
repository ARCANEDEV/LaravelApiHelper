<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers;

use Arcanedev\LaravelApiHelper\Support\Transformer;
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
    /**
     * Transform the instance.
     *
     * @param  \Arcanedev\LaravelApiHelper\Tests\Stubs\Models\Post  $post
     *
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'title'   => $post->title,
            'slug'    => Str::slug($post->title),
            'content' => $post->content,
        ];
    }
}
