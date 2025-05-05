<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class validate_maintenance extends Model
{
    protected $table = 'validate_maintenance';


    // Add fillable fields
    protected $fillable = ['date','valid'];

}
