<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class My_charities extends Eloquent {

    use SoftDeletes;

    /**
     *  array
     *
     */
    protected $fillable = ['title'];

    /**
     * get the Users who have set this Industry as preferred profession
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    function users(){
        return $this->belongsToMany(User::class);
    }
    
}
