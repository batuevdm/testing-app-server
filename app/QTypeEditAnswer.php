<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QTypeEditAnswer extends Model
{
    protected $primaryKey = "ID";
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'qtypeeditanswers';

    public function answer()
    {
        return $this->belongsTo('App\AnswerTypeEasy', 'AnswerID');
    }
}
