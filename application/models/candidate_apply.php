<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Candidate_apply extends Eloquent{

    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'phone','linkedin_url', 'resume'];
 
     /**
 
    
}