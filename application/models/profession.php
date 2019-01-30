<?php


use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Profession extends Eloquent{

    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = ['title'];

    /**
     * get the Users who have set this Profession as preferred profession
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    function users(){
        return $this->belongsToMany(User::class);
    }

    /**
     * get the Professions for this Industry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    function industries(){
        return $this->belongsToMany(Industry::class);
    }

    /**
     * get the Jobs for this Profession
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    function jobs(){
        return $this->hasMany(Job::class);
    }
}