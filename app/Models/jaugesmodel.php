<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jaugesmodel extends Model
{

    protected $table = 'jaugesdates'; 

    protected $fillable = ['date','type_id','equipe'];
    protected $hidden = ['created_at', 'updated_at'];

}
