<?php namespace Arcanedev\LaravelApiHelper\Contracts\Http;

/**
 * Interface  JsonResponse
 *
 * @package   Arcanedev\LaravelApiHelper\Contracts\Http
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface JsonResponse
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

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
    public function success(array $data, $status = 200, $code = 'success', array $headers = [], $options = 0);

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
    public function error($message, $status = 400, $code = 'error', array $headers = [], $options = 0);

    /**
     * Respond with a json response.
     *
     * @param  array   $data
     * @param  int     $status
     * @param  string  $code
     * @param  array   $headers
     * @param  int     $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond(array $data, $status = 200, $code, array $headers = [], $options = 0);
}
