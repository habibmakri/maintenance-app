<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $table = 'stations'; // Specify the table name if it's not the default plural form

    // Add fillable fields
    protected $fillable = ['name', 'distance'];


}
