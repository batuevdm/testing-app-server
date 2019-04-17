<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $primaryKey = "ID";
    public $timestamps = false;
    protected $table = 'usertokens';

    public function user()
    {
        return $this->belongsTo('App\User', 'UserID');
    }
}
