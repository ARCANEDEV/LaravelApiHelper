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
     * @param  array|mixed  $data
     * @param  int          $code
     * @param  array        $headers
     * @param  int          $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data, $code = 200, array $headers = [], $options = 0);

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
    public function error($message, $code = 400, array $headers = [], $options = 0);

    /**
     * Respond with a json response.
     *
     * @param  mixed   $data
     * @param  int     $code
     * @param  string  $status
     * @param  array   $headers
     * @param  int     $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $code = 200, $status = 'success', array $headers = [], $options = 0);
}
