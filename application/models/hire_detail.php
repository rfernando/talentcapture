<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Hire_detail extends Eloquent{

    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = ['start_date', 'base_salary', 'additional_info', 'added_by', 'candidate_id','type','hire_cancelled'];

    /**
     * Get the user who added this candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function added_by(){
        return $this->belongsTo(User::class,'added_by');
    }

    /**
     * Get the candidate to whom the hire Details belong
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function candidate(){
        return $this->belongsTo(Candidate::class,'candidate_id');
    }
}