<?php namespace Arcanedev\LaravelApiHelper\Http;

use Arcanedev\Support\Http\FormRequest as BaseFormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class     FormRequest
 *
 * @package  Arcanedev\LaravelApiHelper\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class FormRequest extends BaseFormRequest
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Status code when the validation fails.
     *
     * @var int
     */
    protected $statusCode = 422;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return $this->expectsJson()
            ? $this->getJsonResponse($errors)
            : parent::response($errors);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the json response with validation errors.
     *
     * @param  array  $errors
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponse($errors)
    {
        return new JsonResponse(
            $this->formatJsonResponseErrors($errors),
            $this->statusCode
        );
    }

    /**
     * Format the json response.
     *
     * @param  array  $errors
     *
     * @return array
     */
    protected function formatJsonResponseErrors(array $errors)
    {
        return [
            'code'     => 'validation_failed',
            'status'   => $this->statusCode,
            'messages' => $errors,
        ];
    }
}
