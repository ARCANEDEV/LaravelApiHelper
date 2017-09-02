<?php namespace Arcanedev\LaravelApiHelper\Tests;

use Arcanedev\LaravelApiHelper\Tests\Stubs\Models\Post;
use Arcanedev\LaravelApiHelper\Tests\Stubs\Models\User;
use Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers\BasicTransformer;
use Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers\FluentTransformer;
use Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers\PostTransformer;
use Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers\UserWithPostsTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;

/**
 * Class     TransformerTest
 *
 * @package  Arcanedev\LaravelApiHelper\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransformerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->createTables();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $transformer = new BasicTransformer($data = ['foo' => 'bar']);

        $this->assertSame(compact('data'), $transformer->transform());
        $this->assertSame(compact('data'), $transformer->toArray());
    }

    /** @test */
    public function it_can_transform_to_response()
    {
        $transformer = new BasicTransformer($data = ['foo' => 'bar']);

        $response = $transformer->toResponse();

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);

        $this->assertSame(200, $response->status());
        $this->assertSame(compact('data'), $response->getData(true));
    }

    /** @test */
    public function it_can_set_extra_data_to_transformer()
    {
        $transformer = BasicTransformer::with(['foo' => 'bar']);

        $transformer->setExtra('code', 'error')
                    ->setExtra('status', 500);

        $this->assertSame([
            'code'   => 'error',
            'data'   => ['foo' => 'bar'],
            'status' => 500,
        ], $transformer->toArray());

        $transformer->withExtras([
            'code'   => 'success',
            'status' => 400
        ]);

        $this->assertSame([
            'code'   => 'success',
            'data'   => ['foo' => 'bar'],
            'status' => 400,
        ], $transformer->toArray());
    }

    /** @test */
    public function it_can_convert_transformer_to_json()
    {
        $transformer = BasicTransformer::with($data = ['foo' => 'bar']);

        $this->assertInstanceOf(\Illuminate\Contracts\Support\Jsonable::class, $transformer);

        $this->assertJsonStringEqualsJsonString(
            json_encode(compact('data')), $transformer->toJson()
        );
    }

    /** @test */
    public function it_can_transform_a_fluent_object()
    {
        $transformer = FluentTransformer::with(new Fluent([
            'title'   => 'Post title',
            'content' => 'Post content',
            'hidden'  => 'secret',
        ]));

        $expected = [
            'data' => [
                'title'   => 'Post title',
                'content' => 'Post content',
            ],
        ];

        $this->assertSame($expected, $transformer->transform());
        $this->assertSame($expected, $transformer->toArray());
    }

    /** @test */
    public function it_can_transform_a_model()
    {
        $transformer = new PostTransformer($this->makePost());

        $expected = [
            'data' => [
                'title'   => 'Post title',
                'slug'    => 'post-title',
                'content' => 'Post content',
            ],
        ];

        $this->assertSame($expected, $transformer->transform());
        $this->assertSame($expected, $transformer->toArray());
    }

    /** @test */
    public function it_can_transform_a_model_with_relationship_and_custom_meta()
    {
        $user = new User([
            'first_name' => 'John',
            'last_name'  => 'DOE',
            'email'      => 'j.doe@example.com',
        ]);
        $user->save();
        $user->posts()->saveMany($this->makePosts(2));

        $transformer = UserWithPostsTransformer::with($user);

        $expected = [
            'data' => [
                'full_name' => 'John DOE',
                'email'     => 'j.doe@example.com',
                'posts'     => [
                    [
                        'title'   => 'Post title',
                        'slug'    => 'post-title',
                        'content' => 'Post content',
                    ],[
                        'title'   => 'Post title',
                        'slug'    => 'post-title',
                        'content' => 'Post content',
                    ],
                ],
            ],
            'meta' => [
                'posts_count' => 2,
            ],
        ];

        $this->assertSame($expected, $transformer->transform());
        $this->assertSame($expected, $transformer->toArray());
    }

    /** @test */
    public function it_can_transform_a_collection()
    {
        $transformer = PostTransformer::with($this->makePosts(2));

        $expected = [
            'data' => [
                [
                    'title'   => 'Post title',
                    'slug'    => 'post-title',
                    'content' => 'Post content',
                ],[
                    'title'   => 'Post title',
                    'slug'    => 'post-title',
                    'content' => 'Post content',
                ],
            ],
        ];

        $this->assertSame($expected, $transformer->transform());
        $this->assertSame($expected, $transformer->toArray());
    }

    /** @test */
    public function it_can_transform_a_paginator()
    {
        $perPage     = 5;
        $currentPage = 1;
        $posts       = $this->makePosts(10);
        $transformer = PostTransformer::with(
            new LengthAwarePaginator($posts->forPage($currentPage, $perPage), $posts->count(), $perPage, $currentPage)
        );
        $transformed = $transformer->transform();

        $expectedItem = [
            'title'   => 'Post title',
            'slug'    => 'post-title',
            'content' => 'Post content',
        ];
        $data = $transformed['data'];

        $this->assertCount(5, $data);
        foreach ($data as $item) {
            $this->assertSame($expectedItem, $item);
        }

        $expectedMeta = [
            'current_page'  => 1,
            'from'          => 1,
            'last_page'     => 2,
            'first_page_url' => '/?page=1',
            'next_page_url' => '/?page=2',
            'last_page_url' => '/?page=2',
            'path'          => '/',
            'per_page'      => 5,
            'prev_page_url' => null,
            'to'            => 5,
            'total'         => 10,
        ];

        $this->assertEquals($expectedMeta, $transformed['meta']);
    }

    /** @test */
    public function it_can_transform_a_paginator_with_custom_meta()
    {
        $perPage     = 2;
        $currentPage = 1;
        $posts       = $this->makePosts(4);
        $transformer = PostTransformer::with(
            new LengthAwarePaginator($posts->forPage($currentPage, $perPage), $posts->count(), $perPage, $currentPage)
        );

        $transformer->setExtra('meta', ['type' => 'paginator']);

        $this->assertEquals([
            'data' => [
                [
                    'title'   => 'Post title',
                    'slug'    => 'post-title',
                    'content' => 'Post content',
                ],[
                    'title'   => 'Post title',
                    'slug'    => 'post-title',
                    'content' => 'Post content',
                ],
            ],
            'meta' => [
                'current_page'   => 1,
                'from'           => 1,
                'last_page'      => 2,
                'first_page_url' => '/?page=1',
                'next_page_url'  => '/?page=2',
                'last_page_url'  => '/?page=2',
                'path'           => '/',
                'per_page'       => 2,
                'prev_page_url'  => null,
                'to'             => 2,
                'total'          => 4,
                'type'           => 'paginator',
            ],
        ], $transformer->transform());
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
