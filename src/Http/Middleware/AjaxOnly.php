<?php namespace Arcanedev\LaravelApiHelper\Http\Middleware;

/**
 * Class     AjaxOnly
 *
 * @package  Arcanedev\LaravelApiHelper\Http\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AjaxOnly
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
        if ($request->ajax())
            return $next($request);

        return json_response()->error('Invalid request', $this->code);
    }
}
