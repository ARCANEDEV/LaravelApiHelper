<?php namespace Arcanedev\LaravelApiHelper\Traits;

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
        return json_response();
    }

    /**
     * Respond with a success response.
     *
     * @param  array   $data
     * @param  int     $status
     * @param  string  $code
     * @param  array   $headers
     * @param  int     $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponseSuccess(array $data, $status = 200, $code = 'success', array $headers = [], $options = 0)
    {
        return $this->jsonResponse()->success($data, $status, $code, $headers, $options);
    }

    /**
     * Respond with an error response.
     *
     * @param  string  $message
     * @param  int     $status
     * @param  string  $code
     * @param  array   $headers
     * @param  int     $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponseError($message, $status = 400, $code = 'error', array $headers = [], $options = 0)
    {
        return $this->jsonResponse()->error($message, $status, $code, $headers, $options);
    }
}
