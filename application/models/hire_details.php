<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Hire_details extends Eloquent{

    /**
     * Fillable Fields for this Model
     */
    protected $fillable = ['id', 'type','hire_cancelled','created_at'];

    /**
     * @var string
     *
     * Primary key for this table
     */
    protected $primaryKey = 'candidate_id';

    /**
     * Get the Profile of this User
     */
    function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Profile of this User
     */
    function job()
    {
        return $this->belongsTo(Job::class);
    }
}