<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pieces_maintanance extends Model
{
    protected $table = 'pieces_maintenance';
    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['name'];
}
