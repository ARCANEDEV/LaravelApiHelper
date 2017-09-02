<?php namespace Arcanedev\LaravelApiHelper\Http;

use ArrayAccess;
use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\DelegatesToResource;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Http\Resources\Json\ResourceResponse;
use Illuminate\Http\Resources\MergeValue;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use JsonSerializable;

/**
 * Class     ApiResource
 *
 * @package  Arcanedev\LaravelApiHelper\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  mixed|null  pivot
 */
class ApiResource implements ArrayAccess, JsonSerializable, Responsable, UrlRoutable
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use DelegatesToResource;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The resource instance.
     *
     * @var mixed
     */
    public $resource;

    /**
     * The additional data that should be added to the top-level resource array.
     *
     * @var array
     */
    public $with = [];

    /**
     * The additional meta data that should be added to the resource response.
     *
     * Added during response construction by the developer.
     *
     * @var array
     */
    public $additional = [];

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'data';

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * PostTransformer constructor.
     *
     * @param  mixed  $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     *
     * @return static
     */
    public static function make($resource)
    {
        return new static($resource);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function with($request)
    {
        return $this->with;
    }

    /**
     * Add additional meta data to the resource response.
     *
     * @param  array  $data
     *
     * @return $this
     */
    public function additional(array $data)
    {
        $this->additional = $data;

        return $this;
    }

    /**
     * Set the string that should wrap the outer-most resource array.
     *
     * @param  string  $value
     */
    public static function wrap($value)
    {
        static::$wrap = $value;
    }

    /**
     * Disable wrapping of the outer-most resource array.
     *
     * @return void
     */
    public static function withoutWrapping()
    {
        static::wrap(null);
    }


    /**
     * Customize the response for a request.
     *
     * @param  \Illuminate\Http\Request       $request
     * @param  \Illuminate\Http\JsonResponse  $response
     *
     * @return void
     */
    public function withResponse($request, $response)
    {
        //
    }

    /**
     * Merge the given attributes.
     *
     * @param  array  $attributes
     *
     * @return \Illuminate\Http\Resources\MergeValue
     */
    protected function attributes($attributes)
    {
        return new MergeValue(
            Arr::only($this->resource->toArray(), $attributes)
        );
    }

    /**
     * Retrieve a relationship if it has been loaded.
     *
     * @param  string  $relationship
     *
     * @return \Illuminate\Http\Resources\MissingValue|mixed
     */
    protected function whenLoaded($relationship)
    {
        return $this->resource->relationLoaded($relationship)
            ? $this->resource->{$relationship}
            : new MissingValue;
    }

    /**
     * Execute a callback if the given pivot table has been loaded.
     *
     * @param  string  $table
     * @param  mixed   $value
     * @param  mixed   $default
     *
     * @return \Illuminate\Http\Resources\MissingValue|mixed
     */
    protected function whenPivotLoaded($table, $value, $default = null)
    {
        if (func_num_args() === 2)
            $default = new MissingValue;

        return $this->when(
            $this->pivot && ($this->pivot instanceof $table || $this->pivot->getTable() === $table),
            ...[$value, $default]
        );
    }

    /**
     * Resolve the resource to an array.
     *
     * @param  \Illuminate\Http\Request|null  $request
     *
     * @return array
     */
    public function resolve($request = null)
    {
        $request = $request ?: Container::getInstance()->make('request');

        if ($this->resource instanceof Collection)
            $data = $this->resolveCollection($this->resource, $request);
        elseif ($this->resource instanceof AbstractPaginator)
            $data = $this->resolveCollection($this->resource->getCollection(), $request);
        else
            $data = $this->toArray($request);

        if ($data instanceof Arrayable)
            $data = $data->toArray();
        elseif ($data instanceof JsonSerializable)
            $data = $data->jsonSerialize();

        return $this->resolveNestedRelations((array) $data, $request);
    }

    /**
     * Resolve the nested resources to an array.
     *
     * @param  array                     $data
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    protected function resolveNestedRelations($data, $request)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->resolveNestedRelations($value, $request);
            }
            elseif ($value instanceof static) {
                $data[$key] = $value->resolve($request);
            }
        }

        return $this->filter($data);
    }

    /**
     * Resolve the resource to an array.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  \Illuminate\Http\Request|null   $request
     *
     * @return array
     */
    public function resolveCollection($collection, $request = null)
    {
        return $collection->map(function ($item) use ($request) {
            return (new static($item))->toArray($request);
        })->all();
    }

    /**
     * Transform the resource into an HTTP response.
     *
     * @param  \Illuminate\Http\Request|null  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function response($request = null)
    {
        return $this->toResponse(
            $request ?: Container::getInstance()->make('request')
        );
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        return (
            $this->resource instanceof AbstractPaginator
                ? new PaginatedResourceResponse($this)
                : new ResourceResponse($this)
        )->toResponse($request);
    }

    /**
     * Retrieve a value based on a given condition.
     *
     * @param  bool   $condition
     * @param  mixed  $value
     * @param  mixed  $default
     *
     * @return \Illuminate\Http\Resources\MissingValue|mixed
     */
    protected function when($condition, $value, $default = null)
    {
        return $condition
            ? value($value)
            : (func_num_args() === 3 ? value($default) : new MissingValue);
    }

    /**
     * Filter the given data, removing any optional values.
     *
     * @param  array  $data
     *
     * @return array
     */
    protected function filter($data)
    {
        $index = -1;

        foreach ($data as $key => $value) {
            $index++;

            if (is_array($value)) {
                $data[$key] = $this->filter($value);

                continue;
            }

            if (is_numeric($key) && $value instanceof MergeValue) {
                return $this->merge($data, $index, $this->filter($value->data));
            }

            if (
                $value instanceof MissingValue ||
                ($value instanceof self && $value->resource instanceof MissingValue)
            ) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Merge the given data in at the given index.
     *
     * @param  array  $data
     * @param  int    $index
     * @param  array  $merge
     *
     * @return array
     */
    protected function merge($data, $index, $merge)
    {
        if (array_values($data) === $data) {
            return array_merge(
                array_merge(array_slice($data, 0, $index, true), $merge),
                $this->filter(array_slice($data, $index + 1, null, true))
            );
        }

        return array_slice($data, 0, $index, true) +
            $merge +
            $this->filter(array_slice($data, $index + 1, null, true));
    }

    /**
     * Merge a value based on a given condition.
     *
     * @param  bool   $condition
     * @param  mixed  $value
     *
     * @return \Illuminate\Http\Resources\MissingValue|mixed
     */
    protected function mergeWhen($condition, $value)
    {
        return $condition ? new MergeValue(value($value)) : new MissingValue;
    }

    /**
     * Transform the given value if it is present.
     *
     * @param  mixed     $value
     * @param  callable  $callback
     * @param  mixed     $default
     *
     * @return mixed
     */
    protected function transform($value, callable $callback, $default = null)
    {
        return transform(
            $value, $callback, func_num_args() === 3 ? $default : new MissingValue
        );
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->resolve(Container::getInstance()->make('request'));
    }
}
