<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QTypeCheckboxAnswer extends Model
{
    protected $primaryKey = "ID";
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'qtypecheckboxanswers';

    public function answer()
    {
        return $this->belongsTo('App\AnswerTypeEasy', 'AnswerID');
    }
}
