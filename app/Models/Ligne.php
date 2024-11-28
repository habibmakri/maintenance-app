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
    protected $fillable = ['name', 'Length', 'arrets', 'maps', 'color'];

    // Define relationships if necessary
    public function buses()
    {
        return $this->hasMany(Bus::class, 'ligne_id');
    }
    public function maintenanceRecords()
{
    return $this->hasMany(FicheMaintenance::class, 'id_bus');  // Link back to FicheMaintenance
}
}
