<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Site_messages extends Eloquent{

    
    /**
     * array of columns that are Fillable
     */
    protected  $fillable = ['type','msg'];


}