<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fichepanne_model extends Model
{
    use HasFactory;

    protected $table = 'fichepanne'; // Specify the table name if it's not the default plural form

    // Add fillable fields
    protected $fillable = ['fichemaintenance_id','pannnename_id','solved'];

    public function fichemaintenance()
    {
        return $this->belongsTo(FicheMaintenance::class, 'fichemaintenance_id');
    }
    public function pannename()
    {
        return $this->belongsTo(Panne::class, 'pannnename_id');
    }
}
