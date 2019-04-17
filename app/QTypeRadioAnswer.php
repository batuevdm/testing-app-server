<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QTypeRadioAnswer extends Model
{
    protected $primaryKey = "ID";
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'qtyperadioanswers';

    public function answer()
    {
        return $this->belongsTo('App\AnswerTypeEasy', 'AnswerID');
    }
}
