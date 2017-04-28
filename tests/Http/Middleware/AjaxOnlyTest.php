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
        /** @var  \Illuminate\Http\JsonResponse  $response */
        $response = $this->json('GET', '/')->response;

        $this->assertTrue($response->isOk());

        $expected = [
            'status'  => 200,
            'code'    => 'success',
            'message' => 'Hello world',
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
            'status'  => 403,
            'code'    => 'error',
            'message' => 'Invalid AJAX Request',
        ];

        $this->assertSame($expected, $response->getData(true));
    }
}
