<?php namespace Arcanedev\LaravelApiHelper\Tests\Http\Middleware;

use Arcanedev\LaravelApiHelper\Tests\TestCase;

/**
 * Class     AjaxOnlyTest
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Http\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AjaxOnlyTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */
    /** @test */
    public function it_can_pass_ajax_request()
    {
        $server   = ['HTTP_X-Requested-With' => 'XMLHttpRequest'];

        /** @var  \Illuminate\Http\JsonResponse  $response */
        $response = $this->call('GET', '/', [], [], [], $server);

        $this->assertTrue($response->isOk());

        $expected = [
            'status' => 'success',
            'code'   => 200,
            'data'   => 'Hello world',
        ];

        $this->assertSame($expected, $response->getData(true));
    }

    /** @test */
    public function it_must_block_non_ajax_request()
    {
        /** @var  \Illuminate\Http\JsonResponse  $response */
        $response = $this->call('GET', '/');

        $this->assertFalse($response->isOk());

        $expected = [
            'status'  => 'error',
            'code'    => 403,
            'message' => 'Invalid request',
        ];

        $this->assertSame($expected, $response->getData(true));
    }
}
