<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ctechnique_rating extends Model
{
    protected $table = 'ctechnique_ratings';

    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'service',
        'controler',
        'clean',
        'order',
        'phone',
        'message',
    ];

}
