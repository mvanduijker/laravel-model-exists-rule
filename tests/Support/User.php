<?php

namespace Duijker\LaravelModelExistsRule\Tests\Support;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
