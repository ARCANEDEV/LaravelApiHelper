<?php namespace Arcanedev\LaravelApiHelper\Http;

use Arcanedev\LaravelApiHelper\Contracts\Http\JsonResponse as JsonResponseContract;
use Illuminate\Contracts\Routing\ResponseFactory;

/**
 * Class     JsonResponder
 *
 * @package  Arcanedev\LaravelApiHelper\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class JsonResponder implements JsonResponseContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The response factory.
     *
     * @var  \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * JsonResponse constructor.
     *
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

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
    public function success(array $data = [], $status = 200, $code = 'success', array $headers = [], $options = 0)
    {
        return $this->respond($data, $status, $code, $headers, $options);
    }

    /**
     * Respond with an error response.
     *
     * @param  array   $data
     * @param  int     $status
     * @param  string  $code
     * @param  array   $headers
     * @param  int     $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(array $data = [], $status = 400, $code = 'error', array $headers = [], $options = 0)
    {
        return $this->respond($data, $status, $code, $headers, $options);
    }

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
    public function respond(array $data, $status = 200, $code, array $headers = [], $options = 0)
    {
        return $this->response->json(
            array_merge(compact('status', 'code'), $data), $status, $headers, $options
        );
    }
}
