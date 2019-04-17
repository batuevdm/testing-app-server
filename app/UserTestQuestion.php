<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTestQuestion extends Model
{
    protected $primaryKey = "ID";
    public $timestamps = false;
    protected $table = 'usertestquestions';
    protected $hidden = ['question'];

    public function question()
    {
        return $this->belongsTo('App\Question', 'QuestionID');
    }
}
