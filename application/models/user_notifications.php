<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class User_notifications extends Eloquent{

    use SoftDeletes;

    /**
     * array of columns that are Fillable
     */
    protected  $fillable = ['notification_text','notification_url','status','cn_status','user_id'];


}