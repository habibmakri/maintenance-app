<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class admin_agents extends Model
{
    use SoftDeletes;

    protected $table = 'admin_agents';

    protected $fillable = ['firstname', 'lastname'];

}
