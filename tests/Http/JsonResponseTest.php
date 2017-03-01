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
        $data     = ['foo' => 'bar'];
        $response = $this->jsonResponse->success($data);

        $this->assertJsonResponse($response);

        $this->assertSame([
            'status' => 'success',
            'code'   => 200,
            'data'   => $data,
        ], $response->getData(true));
    }

    /** @test */
    public function it_can_respond_with_error_response()
    {
        $message  = 'Post with not found';
        $code     = 404;
        $response = $this->jsonResponse->error($message, $code);

        $this->assertJsonResponse($response);

        $this->assertSame([
            'status'  => 'error',
            'code'    => $code,
            'message' => $message,
        ], $response->getData(true));
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
    protected function assertJsonResponse($response)
    {
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
    }
}
