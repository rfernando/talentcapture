<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Mail extends Eloquent{

    protected  $fillable = ['to','from','subject', 'body'];
}