<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $primaryKey = "ID";
    public $timestamps = false;
    protected $table = 'tests';
    protected $hidden = ['questions', 'hide'];

    public function questions()
    {
        return $this->belongsToMany('App\Question', 'testquestions', 'TestID', 'QuestionID')
            ->withPivot('Number')
            ->withPivot('Mark')
            ->orderBy('Number');
    }
}
