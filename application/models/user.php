<?php

//use Elegant\Model as Timex;
use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;


class User extends Eloquent {

    use SoftDeletes;

    /**
     * array of columns that are Fillable
     */
    protected  $fillable = ['email', 'password', 'first_name', 'last_name', 'type', 'phone', 'register_mode'];

    /**
     * get the Profile of this User
     */
    function user_profile()
    {
        return $this->hasOne(User_profile::class);
    }

    /**
     * get the Job Categories in which this user specializes
     */
    function job_categories()
    {
        return $this->belongsToMany(Job_category::class,'speciality_areas');
    }

    /**
     * get the Industries in which this user specializes
     */
    function industries()
    {
        return $this->belongsToMany(Industry::class);
    }

    /**
     * get the Profession in which this user specializes
     */
    function professions()
    {
        return $this->belongsToMany(Profession::class);
    }

    /**
     * get the list of Jobs accepted by the user
     */
    function accepted_jobs(){
        return $this->belongsToMany(Job::class, 'accepted_jobs');
    }

    /**
     * get the list of Jobs accepted by the user
     */
    function rejected_jobs(){
        return $this->belongsToMany(Job::class, 'rejected_jobs');
    }

    /**
     * get the list of Jobs created by the user
     */
    function created_jobs(){
        return $this->hasMany(Job::class);
    }

    /**
     * get favourite Agencies
     */
    function favourite_agencies(){
        return $this->belongsToMany(User::class,'preferred_agencies', 'user_id', 'agency_id');
    }

    /**
     * get ratings given by this user.
     */
    function ratings(){
        return $this->belongsToMany(User::class,'rating', 'user_id', 'agency_id','rat_status');
    }


    /**
     * Ratings given to this user.
     */
    function agency_ratings(){
        return $this->belongsToMany(User::class,'rating', 'agency_id', 'user_id','rat_status')->where('rat_status',1)->withPivot('rating', 'review','rat_status');
    }


    function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * get the Charities in which this user selected
     */
    function my_charities()
    {
        return $this->belongsToMany(My_charities::class);
    }

}