<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Models;

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
class Post extends Model
{
    public $table      = 'posts';
    public $timestamps = false;
    protected $guarded = ['id'];
}
