<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class maintenance_agent extends Model
{
    use SoftDeletes;

    protected $table = 'maintenance_agents';

    protected $fillable = ['firstname', 'lastname'];

}
