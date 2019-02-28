<?php namespace Arcanedev\LaravelApiHelper\Tests\Http;

use Arcanedev\LaravelApiHelper\Http\ApiResource;
use Arcanedev\LaravelApiHelper\Tests\Stubs\Models\User;
use Arcanedev\LaravelApiHelper\Tests\TestCase;

/**
 * Class     ApiResourceTest
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ApiResourceTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
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
        $user = User::query()->create([
            'first_name' => 'John',
            'last_name'  => 'DOE',
            'email'      => 'j.doe@example.com',
        ]);

        static::assertJson($content = UserResource::make($user)->response()->content());
        static::assertSame([
            'data' => [
                'hashed_id' => 'hashed_id_here',
                'full_name' => 'John DOE',
                'email'     => 'j.doe@example.com',
            ],
        ], json_decode($content, true));
    }
}

/**
 * Class     UserResource
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin  \Arcanedev\LaravelApiHelper\Tests\Stubs\Models\User
 */
class UserResource extends ApiResource
{
    public function toArray($request)
    {
        return [
            'hashed_id' => 'hashed_id_here',
            'full_name' => $this->full_name,
            'email'     => $this->email,
        ];
    }
}

class UserWithPostsResource extends UserResource
{
    public function toArray($request)
    {
        return parent::toArray($request) + [
            'posts' => new PostResource($this->posts)
        ];
    }
}

/**
 * Class     PostResource
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin  \Arcanedev\LaravelApiHelper\Tests\Stubs\Models\Post
 */
class PostResource extends ApiResource
{
    public function toArray($request)
    {
        return [
            'title'   => $this->title,
            'slug'    => str_slug($this->title),
            'content' => $this->content,
        ];
    }
}
