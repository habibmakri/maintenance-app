<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $table = 'buses';

    protected $hidden = ['created_at', 'updated_at'];


    protected $fillable = ['name', 'type','kmactuelle','kmderniervidange','derniervidange' ,'derniervidangeboite','derniervidangepond','ligne_id','vidange_moteur_date','vidange_boite_date','vidange_pond_date'];

    public function ligne()
    {
        return $this->belongsTo(Ligne::class);
    }
    public function maintenanceRecords()
    {
        return $this->hasMany(fichemaintenance::class, 'id_bus');
    }
    public function traveauxlibre()
    {
        return $this->hasMany(traveauxlibre_model::class, 'id_bus');
    }
}
