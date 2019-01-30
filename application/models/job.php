<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Job extends Eloquent{

    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = [
        'title', 'industry_id','profession_id', 'notify_preferred', 'skills','job_location', 'position_type', 'client_name','client_name_confidential','salary','payment_terms', 'warranty_period','placement_fee','split_percentage', 'note', 'description','question','user_id','visa_sponsorship','relocate','add_agency','openings']; //adding new column opening for the RP-787

    /**
     * get the Job category to which this job belongs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function job_category(){
        return $this->belongsTo(Job_category::class);
    }

    /**
     * get the Industry to which this job belongs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function industry(){
        return $this->belongsTo(Industry::class);
    }

    /**
     * get the Profession to which this job belongs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function profession(){
        return $this->belongsTo(Profession::class);
    }

    /**
     * Get the user who has created this Job
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for Active Jobs
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query){
        return $query->where('status', 1);
    }

    /**
     * Get the user who has created this Job
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function candidates(){
        return $this->hasMany(Candidate::class);
    }

    /**
     * Get the user who have accepted this Job
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function accepted_by_agencies(){
        return $this->belongsToMany(User::class,'accepted_jobs', 'job_id', 'user_id');
        //return $this->hasManyThrough(User::class, Candidate::class, 'job_id', 'id');
        /*return $this->hasMany(Candidate::class)
            ->join('users', 'users.id' , '=', 'candidates.user_id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->select('users.id','users.profile_pic','user_profiles.company_name')->groupBy('users.id');*/
    }

    function accepted_by_agency(){
        return $this->belongsToMany(User::class,'accepted_jobs', 'job_id', 'user_id');
    }


    function scopeOpen($query)
    {
        return $query->where('closed', 0);
    }

}