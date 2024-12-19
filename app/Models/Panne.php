<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Panne extends Model
{
    use HasFactory;

    protected $table = 'pannenames'; // Specify the table name if it's not the default plural form

    // Add fillable fields
    protected $fillable = ['name','type'];

    // Define relationships if necessary
}
