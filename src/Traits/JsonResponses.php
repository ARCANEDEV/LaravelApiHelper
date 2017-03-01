<?php namespace Arcanedev\LaravelApiHelper\Traits;

use Arcanedev\LaravelApiHelper\Contracts\Http\JsonResponse;

/**
 * Class     JsonResponses
 *
 * @package  Arcanedev\LaravelApiHelper\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait JsonResponses
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Get the json response instance.
     *
     * @return \Arcanedev\LaravelApiHelper\Contracts\Http\JsonResponse
     */
    public function jsonResponse()
    {
        return app(JsonResponse::class);
    }

    /**
     * Respond with a success response.
     *
     * @param  array|mixed  $data
     * @param  int          $code
     * @param  array        $headers
     * @param  int          $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponseSuccess($data, $code = 200, array $headers = [], $options = 0)
    {
        return $this->jsonResponse()->success($data, $code, $headers, $options);
    }

    /**
     * Respond with an error response.
     *
     * @param  string  $message
     * @param  int     $code
     * @param  array   $headers
     * @param  int     $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponseError($message, $code = 400, array $headers = [], $options = 0)
    {
        return $this->jsonResponse()->error($message, $code, $headers, $options);
    }
}
