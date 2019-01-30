<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Admin_blogs extends Eloquent{

    use SoftDeletes;

    /**
     * array of columns that are Fillable
     */
    protected  $fillable = ['blog_title','blog_desc','status','view_by'];


}