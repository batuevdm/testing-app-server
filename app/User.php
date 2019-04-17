<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = "ID";
    public $timestamps = false;
    protected $hidden = [
        'Password',
        'Role'
    ];

    public function tests()
    {
        return $this->hasMany('App\UserTest', 'UserID')
            ->orderBy("ID", "DESC");
    }
}
