<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $primaryKey = "ID";
    public $timestamps = false;
    protected $table = 'questions';
}
