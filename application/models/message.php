<?php


use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Message extends Eloquent{
    
    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = ['from_user_id', 'text', 'to_user_id','type','candidate_id','job_id'];

    public function to_user(){
        return $this->belongsTo(User::class, 'to_user_id');
    }


    public function from_user(){
        return $this->belongsTo(User::class, 'from_user_id');
    }
}