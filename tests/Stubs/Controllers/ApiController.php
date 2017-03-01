<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Controllers;

use Arcanedev\LaravelApiHelper\Traits\JsonResponses;

/**
 * Class     ApiController
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Stubs\Controllers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ApiController
{
    use JsonResponses;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    public function index()
    {
        return $this->jsonResponseSuccess('Hello world');
    }

    public function show($slug)
    {
        if ($slug == 'valid-slug')
            return $this->jsonResponseSuccess([
                'title'   => 'Post title',
                'content' => 'Post content',
            ]);

        return $this->jsonResponseError("Post not found with the given slug [$slug]", 404);
    }
}
