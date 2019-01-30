<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Message_templates extends Eloquent{

	//protected $table = "message_templates"; // table name

    use SoftDeletes;
    /**
     * array of columns that are Fillable
     */ 
    protected $fillable = ['id', 'subject', 'template_name','template'];

}