<?php namespace Arcanedev\LaravelApiHelper\Http\Middleware;

use Arcanedev\Support\Http\Middleware;

/**
 * Class     AjaxOnly
 *
 * @package  Arcanedev\LaravelApiHelper\Http\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AjaxOnly extends Middleware
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Status code to respond with
     *
     * @var int
     */
    protected $code = 403;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if ($request->expectsJson()) {
            return $next($request);
        }

        return json_response()->error([
            'message' => 'Invalid AJAX Request',
        ], $this->code);
    }
}
