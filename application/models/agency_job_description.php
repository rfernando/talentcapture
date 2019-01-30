<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Agency_job_description extends Eloquent{

    protected $table = 'agency_job_description';

    use SoftDeletes;
    /**
     * array of columns that are Fillable
     */  
    protected $fillable = ['agency_id', 'job_id', 'job_description','added_by'];

}