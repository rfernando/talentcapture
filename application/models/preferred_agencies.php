<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class preferred_agencies extends Eloquent{

    protected $table = 'preferred_agencies';

//    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = ['user_id', 'agency_id'];

}