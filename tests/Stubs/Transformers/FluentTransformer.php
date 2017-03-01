<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers;

use Arcanedev\LaravelApiHelper\Support\Transformer;
use Illuminate\Support\Fluent;

/**
 * Class     FluentTransformer
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Stubs\Transformers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FluentTransformer extends Transformer
{
    /**
     * Transform the item.
     *
     * @param  Fluent  $item
     *
     * @return array
     */
    public function transform(Fluent $item)
    {
        return [
            'title'   => $item->get('title'),
            'content' => $item->get('content'),
        ];
    }
}
