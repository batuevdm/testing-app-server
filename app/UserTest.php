<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    protected $primaryKey = "ID";
    public $timestamps = false;
    protected $table = 'usertests';
    protected $hidden = ['test', 'user', 'userTestQuestions'];

    public function user()
    {
        return $this->belongsTo('App\User', 'UserID');
    }

    public function test()
    {
        return $this->belongsTo('App\Test', 'TestID');
    }

    public function userTestQuestions()
    {
        return $this->hasMany('App\UserTestQuestion', 'UserTestID');
    }
}
