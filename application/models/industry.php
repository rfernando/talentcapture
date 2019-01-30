<?php


use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;


class Industry extends Eloquent {

    use SoftDeletes;

    /**
     * @var array
     *
     */
    protected $fillable = ['title'];

    /**
     * Get the list of professions for this Job
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    function  professions(){
        return $this->belongsToMany(Profession::class);
    }

    /**
     * get the Users who have set this Industry as preferred profession
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    function users(){
        return $this->belongsToMany(User::class);
    }

    /**
     * get the Jobs for this Industry
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    function jobs(){
        return $this->hasMany(Job::class);
    }

    
}