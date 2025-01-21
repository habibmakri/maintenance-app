<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ctechniqueclienttypes extends Model
{

    protected $table = 'ctechniqueclienttypes';

    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'mois',
        'prix',
    ];


}
