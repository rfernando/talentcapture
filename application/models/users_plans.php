<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Users_plans extends Eloquent{

    use SoftDeletes;

    /**
     * array of columns that are Fillable
     */
    protected  $fillable = ['plan_name','no_of_days','amount','status'];

    function user(){
        return $this->belongsTo(User::class);
    }


}