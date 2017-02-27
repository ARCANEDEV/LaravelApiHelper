<?php

return [
    /* -----------------------------------------------------------------
     |  Middleware
     | -----------------------------------------------------------------
     */
    'middleware' => [
        'ajax' => \Arcanedev\LaravelApiHelper\Http\Middleware\AjaxOnly::class
    ],
];
