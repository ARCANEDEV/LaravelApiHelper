<?php namespace Arcanedev\LaravelApiHelper\Tests\Support;

use Arcanedev\LaravelApiHelper\Tests\Stubs\Models\Post;
use Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers\InvalidTransformer;
use Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers\PostTransformer;
use Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers\FluentTransformer;
use Arcanedev\LaravelApiHelper\Tests\TestCase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

/**
 * Class     TransformerTest
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Support
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransformerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */
    /** @test */
    public function it_can_transform_a_fluent_object()
    {
        $data = FluentTransformer::make(new Fluent([
            'title'   => 'Post title',
            'content' => 'Post content',
            'hidden'  => 'secret',
        ]));

        $this->assertSame([
            'title'   => 'Post title',
            'content' => 'Post content',
        ], $data);
    }

    /** @test */
    public function it_can_transform_a_model_with_a_closure()
    {
        $post = $this->makePost();
        $data = $post->transform(function (Post $post) {
            return [
                'title'   => $post->title,
                'slug'    => Str::slug($post->title),
                'content' => $post->content,
            ];
        });

        $this->assertInstanceOf(\Illuminate\Support\Fluent::class, $data);

        $this->assertSame([
            'title'   => 'Post title',
            'slug'    => 'post-title',
            'content' => 'Post content',
        ], $data->getAttributes());
    }

    /** @test */
    public function it_can_transform_a_model_with_a_transformer()
    {
        $data = $this->makePost()
                     ->transform(new PostTransformer);

        $this->assertInstanceOf(\Illuminate\Support\Fluent::class, $data);

        $expected = [
            'title'   => 'Post title',
            'slug'    => 'post-title',
            'content' => 'Post content',
        ];

        $this->assertSame($expected, $data->getAttributes());
    }

    /** @test */
    public function it_can_transform_a_collection_with_a_transformer()
    {
        $posts = $this->makePosts();

        $data = $posts->transform(new PostTransformer);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);
        $this->assertCount(3, $data);

        $expected = [
            'title'   => 'Post title',
            'slug'    => 'post-title',
            'content' => 'Post content',
        ];

        foreach ($data as $item) {
            $this->assertSame($expected, $item);
        }
    }

    /** @test */
    public function it_can_transform_a_paginator_with_a_transformer_option_one()
    {
        $perPage     = 5;
        $currentPage = 1;
        $posts       = $this->makePosts(10)->slice($currentPage * $perPage, $perPage);
        $paginator   = new LengthAwarePaginator($posts, $posts->count(), $perPage, $currentPage);

        $data = PostTransformer::make($paginator);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);
        $this->assertCount($perPage, $data);

        $expected = [
            'title'   => 'Post title',
            'slug'    => 'post-title',
            'content' => 'Post content',
        ];

        foreach ($data as $item) {
            $this->assertSame($expected, $item);
        }
    }

    /** @test */
    public function it_can_transform_a_paginator_with_a_transformer_option_two()
    {
        $perPage     = 5;
        $currentPage = 1;
        $posts       = $this->makePosts(10)->slice($currentPage * $perPage, $perPage);
        $paginator   = new LengthAwarePaginator($posts, $posts->count(), $perPage, $currentPage);

        $data = $paginator->getCollection()->transform(new PostTransformer);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $data);
        $this->assertCount($perPage, $data);

        $expected = [
            'title'   => 'Post title',
            'slug'    => 'post-title',
            'content' => 'Post content',
        ];

        foreach ($data as $item) {
            $this->assertSame($expected, $item);
        }
    }

    /**
     * @test
     *
     * @expectedException         \BadMethodCallException
     * @expectedExceptionMessage  Missing [transform] method in Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers\InvalidTransformer.
     */
    public function it_must_throw_an_exception_on_missing_transform_method()
    {
        InvalidTransformer::make([]);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    protected function makePost()
    {
        return new Post([
            'title'   => 'Post title',
            'content' => 'Post content',
        ]);
    }

    protected function makePosts($count = 3)
    {
        $posts = collect();

        for ($i = 0; $i < $count; $i++) {
            $posts->push($this->makePost());
        }

        return $posts;
    }
}
