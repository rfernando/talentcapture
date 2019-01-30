<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class User_profile extends Eloquent{

    /**
     * Fillable Fields for this Model
     */
    protected $fillable = ['company_name', 'company_website_url', 'role','company_address','city','state_id','zipcode','company_desc','linkedin_url'];

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
    function state()
    {
        return $this->belongsTo(State::class);
    }
}