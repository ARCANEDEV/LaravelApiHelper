<?php namespace Arcanedev\LaravelApiHelper\Tests\Http;

use Arcanedev\LaravelApiHelper\Tests\TestCase;

/**
 * Class     JsonResponseTest
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class JsonResponseTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelApiHelper\Contracts\Http\JsonResponse */
    protected $jsonResponse;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->jsonResponse = $this->app->make(\Arcanedev\LaravelApiHelper\Contracts\Http\JsonResponse::class);
    }

    public function tearDown()
    {
        unset($this->jsonResponse);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_respond_with_success_response()
    {
        $response = $this->jsonResponse->success(['foo' => 'bar']);

        static::assertJsonResponse($response);

        $expected = [
            'status' => 200,
            'code'   => 'success',
            'foo'    => 'bar',
        ];

        static::assertSame($expected, $response->getData(true));
    }

    /** @test */
    public function it_can_respond_with_error_response()
    {
        $message  = 'Post with not found';
        $status   = 404;
        $response = $this->jsonResponse->error(compact('message'), $status);

        $expected = [
            'status'  => $status,
            'code'    => 'error',
            'message' => $message,
        ];

        static::assertJsonResponse($response);
        static::assertSame($expected, $response->getData(true));
    }

    /** @test */
    public function it_can_respond_with_success_response_via_trait()
    {
        /** @var  \Illuminate\Http\JsonResponse  $response */
        $response = $this->json('GET', '/valid-slug')->baseResponse;

        static::assertJsonResponse($response);
        static::assertTrue($response->isOk());

        $expected = [
            'status'  => 200,
            'code'    => 'success',
            'title'   => 'Post title',
            'content' => 'Post content',
        ];

        static::assertSame($expected, $response->getData(true));
    }

    /** @test */
    public function it_can_respond_with_error_response_via_trait()
    {
        /** @var  \Illuminate\Http\JsonResponse  $response */
        $response = $this->json('GET', '/invalid-slug')->baseResponse;

        static::assertJsonResponse($response);
        static::assertFalse($response->isOk());

        $expected = [
            'status'  => 404,
            'code'    => 'error',
            'message' => 'Post not found with the given slug [invalid-slug]',
        ];

        static::assertSame($expected, $response->getData(true));
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Assert that the given response is a json response.
     *
     * @param  \Illuminate\Http\JsonResponse  $response
     */
    public static function assertJsonResponse($response)
    {
        static::assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
    }
}
