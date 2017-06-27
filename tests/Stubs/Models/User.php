<?php namespace Arcanedev\LaravelApiHelper\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class     User
 *
 * @package  Arcanedev\LaravelApiHelper\Tests\Stubs\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string                                    full_name
 * @property  string                                    email
 * @property  \Illuminate\Database\Eloquent\Collection  posts
 */
class User extends Model
{
    public $table      = 'users';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
}
