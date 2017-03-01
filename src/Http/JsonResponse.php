<?php namespace Arcanedev\LaravelApiHelper\Http;

use Arcanedev\LaravelApiHelper\Contracts\Http\JsonResponse as JsonResponseContract;
use Illuminate\Contracts\Routing\ResponseFactory;

/**
 * Class     JsonResponse
 *
 * @package  Arcanedev\LaravelApiHelper\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class JsonResponse implements JsonResponseContract
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
     * @param  array|mixed  $data
     * @param  int          $code
     * @param  array        $headers
     * @param  int          $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data, $code = 200, array $headers = [], $options = 0)
    {
        return $this->respond(compact('data'), $code, 'success', $headers, $options);
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
    public function error($message, $code = 400, array $headers = [], $options = 0)
    {
        return $this->respond(compact('message'), $code, 'error', $headers, $options);
    }

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
    public function respond($data, $code = 200, $status = 'success', array $headers = [], $options = 0)
    {
        $data = array_merge(compact('status', 'code'), $data);

        return $this->response->json($data, $code, $headers, $options);
    }
}
