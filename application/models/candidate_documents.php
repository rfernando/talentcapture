<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;

class Candidate_documents extends Eloquent{

    protected $table = 'candidate_documents';

    use SoftDeletes;

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = ['title', 'file_path', 'candidate_id'];

}