<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Recruiter_notes extends Eloquent{

    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = ['feedback', 'user_id', 'candidate_id'];

    /**
     * Get the user who added this candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 protected $primaryKey = 'candidate_id';
     */
	protected $primaryKey = 'candidate_id';
    function candidate(){
        return $this->belongsTo(Candidate::class);
    }

    /**
     * get the job for which a candidate has Applied
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function  added_by(){
        return $this->belongsTo(User::class,'user_id');
    }
}