<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Email_templates extends Eloquent{

    use SoftDeletes;

    /**
     * array of columns that are Fillable
     */
    protected  $fillable = ['template_name','template_subject','template_body','status'];


}