<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class job_preferred_agencies extends Eloquent{

    protected $table = 'job_preferred_agencies';

//    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = ['job_id', 'agency_id'];

}