<?php


use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Candidate_mail_messaging extends Eloquent{
    
    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = ['job_owner_id', 'candidate_owner_id', 'candidate_id','job_id','conversation_subject','last_conversation'];
}