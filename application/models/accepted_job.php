<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Accepted_job extends Eloquent{

    /**
     * Fillable Fields for this Model
     */
    protected $fillable = ['user_id', 'job_id','estatus','created_at','updated_at'];

    /**
     * @var string
     *
     * Primary key for this table
     */
    protected $primaryKey = 'accepted_job_id';

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