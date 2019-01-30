<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Rejected_job extends Eloquent{

    /**
     * Fillable Fields for this Model
     */
    protected $fillable = ['user_id', 'job_id','created_at'];

    /**
     * @var string
     *
     * Primary key for this table
     */
    protected $primaryKey = 'user_id';

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