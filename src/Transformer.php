<?php namespace Arcanedev\LaravelApiHelper;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class     Transformer
 *
 * @package  Arcanedev\LaravelApiHelper
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @deprecated Since v2.2, use \Arcanedev\LaravelApiHelper\Http\Resource instead.
 */
abstract class Transformer implements Arrayable, Jsonable
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  mixed */
    protected $resource;

    /** @var array */
    protected $extras = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Transformer constructor.
     *
     * @param  mixed  $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Add extras data to the response.
     *
     * @param  array  $extras
     *
     * @return $this
     */
    public function withExtras(array $extras)
    {
        $this->extras = $extras;

        return $this;
    }

    /**
     * Add meta data to be associated with the response.
     *
     * @param  array  $data
     *
     * @return $this
     */
    public function withMeta(array $data)
    {
        return $this->setExtra('meta', Arr::sortRecursive($data));
    }

    /**
     * Get the value from the extra data.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function getExtra($key, $default = null)
    {
        return Arr::get($this->extras, $key, $default);
    }

    /**
     * Set an extra data to the response.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return $this
     */
    public function setExtra($key, $value)
    {
        $this->extras[$key] = $value;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a new instance of the response.
     *
     * @param  mixed  $resource
     *
     * @return static
     */
    public static function with($resource)
    {
        return new static($resource);
    }

    /**
     * Get a displayable API output for the given object.
     *
     * @return array
     */
    public function transform()
    {
        $object = $this->resource;

        $data = $object instanceof Collection || $object instanceof AbstractPaginator
            ? $object->map([$this, 'transformResource'])->toArray()
            : $this->transformResource($object);

        if ($object instanceof AbstractPaginator) {
            $this->withMeta(array_merge(
                $this->getExtra('meta', []), Arr::except($object->toArray(), ['data'])
            ));
        }

        $data = array_filter(compact('data') + $this->extras);
        ksort($data);

        return $data;
    }

    /**
     * Get a displayable API output for the given sub-resource object.
     *
     * @param  mixed  $resource
     *
     * @return array
     */
    public static function subResource($resource)
    {
        return Arr::get(static::with($resource)->toArray(), 'data');
    }

    /**
     * Transform the given resource for the API output.
     *
     * @param  mixed  $resource
     *
     * @return array
     */
    abstract public function transformResource($resource);

    /**
     * Get the instance as a json response object.
     *
     * @param  int    $status
     * @param  array  $headers
     * @param  int    $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($status = 200, array $headers = [], $options = 0)
    {
        return new JsonResponse($this->transform(), $status, $headers, $options);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->transform();
    }

    /**
     * Get the instance as an array.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
