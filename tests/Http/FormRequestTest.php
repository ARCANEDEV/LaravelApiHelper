<?php namespace Arcanedev\LaravelApiHelper\Tests\Http;

use Arcanedev\LaravelApiHelper\Tests\TestCase;

/**
 * Class     FormRequestTest
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FormRequestTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_fail_validation_with_form_request()
    {
        $response = $this->json('POST', 'form-request');

        $response->assertStatus(422);

        static::assertJson($content = $response->content());

        static::assertSame([
            'message' => 'The given data was invalid.',
            'errors'  => [
                'name'  => [
                    'The name field is required.',
                ],
                'email' => [
                    'The email field is required.',
                ],
            ],
        ], json_decode($content, true));
    }

    /** @test */
    public function it_can_validate_form_request()
    {
        $response = $this->json('POST', 'form-request', [
            'name'  => 'ARCANEDEV',
            'email' => 'arcanedev@example.com',
        ]);

        $response->assertSuccessful();

        static::assertJson($content = $response->content());
        static::assertSame(json_encode([
            'status'  => 200,
            'code'    => 'success',
            'message' => 'Everything is good !',
        ]), $content);
    }
}
