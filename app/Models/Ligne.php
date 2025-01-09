<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ligne extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lignes'; // Specify the table name if it's not the default plural form

    // Add fillable fields
    protected $fillable = ['name','station_id','terminus',  'arrets'];

    // Define relationships if necessary
    public function buses()
    {
        return $this->hasMany(Bus::class, 'ligne_id');
    }
    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id');  // Link back to FicheMaintenance
    }
    public function maintenanceRecords()
{
    return $this->hasMany(fichemaintenance::class, 'id_bus');  
}
}
