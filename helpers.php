<?php

use Arcanedev\LaravelApiHelper\Contracts\Http\JsonResponse;

if ( ! function_exists('json_response')) {
    /**
     * Get the JsonResponse instance.
     *
     * @return JsonResponse
     */
    function json_response() {
        return app(JsonResponse::class);
    }
}
