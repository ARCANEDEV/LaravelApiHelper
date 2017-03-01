<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Models;

use Arcanedev\LaravelApiHelper\Contracts\Transformable;
use Arcanedev\LaravelApiHelper\Traits\TransformableModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class     Post
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Stubs\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string  title
 * @property  string  content
 */
class Post extends Model implements Transformable
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */
    use TransformableModel;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content'];
}
