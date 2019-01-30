<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
//use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Free_trial extends Eloquent{

    //use SoftDeletes;

    /**
    * array of columns that are Fillable
    */
    protected  $fillable = ['agency_id','no_of_days','plan_id'];

    /***
     * get free trial day
     */
    function get_freetrialday(){
        return $this->hasOne(Free_trial::class);
    }




}