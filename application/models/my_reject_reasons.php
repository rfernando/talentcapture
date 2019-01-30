<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class My_reject_reasons extends Eloquent {

    use SoftDeletes;

    /**
     *  array
     *
     */
    protected $fillable = ['reject_option'];

    /**
     * get the Users who have set this Industry as preferred profession
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    function users(){
        return $this->belongsToMany(User::class);
    }
    
}
