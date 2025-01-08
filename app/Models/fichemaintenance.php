<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fichemaintenance extends Model
{
    // The table associated with the model.
    protected $table = 'fiches_maintenance';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped (created_at and updated_at).
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];


    // The attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'date_fiche',
        'declaré',
        'id_chauffeur',
        'id_bus',
        'id_ligne',
        'brigade',
        'heur_depart',
        'heur_arrive',
        'gasoile',
        'kmdepart',
        'kmarrive',
        'kmhlp',
        'kmgobale',
        'kmcommerciale',
    ];

    // Define the relationship between fichemaintenance and bus
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'id_bus', 'id');
    }

    // Define the relationship between fichemaintenance and ligne
    public function ligne()
    {
        return $this->belongsTo(Ligne::class, 'id_ligne', 'id');
    }
    public function chauffeur()
    {
        return $this->belongsTo(chauffeurs::class, 'id_chauffeur', 'id');
    }
    public function fichepanne()
    {
        return $this->hasmany(fichepanne_model::class, 'fichemaintenance_id', 'id');
    }

    
    // Optionally, you can add additional methods or logic here if needed
}