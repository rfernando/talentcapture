<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Candidate extends Eloquent{

    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'phone','linkedin_url','facebook_url', 'city','state_id','salary','residency', 'will_relocate', 'employment_history','notes','user_id','job_id', 'resume'];             // For RP-804 Adding a new column salary and residency
 
     /**
 
    /**
     * Get the user who added this Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function agency(){
        return $this->belongsTo(User::class,'user_id');
    }

    function state(){
        return $this->belongsTo(State::class,'state_id');
    }

    /**
     * get the job for which a Candidate has Applied
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function  applied_job(){
        return $this->belongsTo(Job::class,'job_id');
    }

    /**
     * get the Employer feedback for this Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function  feedback(){
        return $this->hasMany(Candidate_feedback::class,'candidate_id');
    }

    /**
     * get the Recruiter Notes for this Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function  notes(){
        return $this->hasMany(Recruiter_notes::class,'candidate_id');
    }

    /**
     * get the Hire details for this Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    function hire_details(){
        return $this->hasMany(Hire_detail::class, 'candidate_id');
    }
}