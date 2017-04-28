<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Controllers;

use Arcanedev\LaravelApiHelper\Http\FormRequest;
use Arcanedev\LaravelApiHelper\Traits\JsonResponses;

/**
 * Class     ApiController
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Stubs\Controllers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ApiController
{
    /* -----------------------------------------------------------------
     |  Trait
     | -----------------------------------------------------------------
     */

    use JsonResponses;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function index()
    {
        return $this->jsonResponseSuccess(['message' => 'Hello world']);
    }

    public function show($slug)
    {
        if ($slug == 'valid-slug') {
            return $this->jsonResponseSuccess([
                'title'   => 'Post title',
                'content' => 'Post content',
            ]);
        }

        return $this->jsonResponseError([
            'message' => "Post not found with the given slug [$slug]",
        ], 404);
    }

    public function form(JsonFormRequest $request)
    {
        return $this->jsonResponseSuccess([
            'message' => 'Everything is good !',
        ]);
    }
}

class JsonFormRequest extends FormRequest
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'  => ['required', 'string', 'min:6'],
            'email' => ['required', 'string', 'email'],
        ];
    }
}
