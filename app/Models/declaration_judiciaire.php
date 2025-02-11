<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class declaration_judiciaire extends Model
{
    protected $table = 'declaration_judiciaire';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [	
	'number',
	'date_fiche',
	'caat',
	'paye',
	'id_bus',
	'id_chauffeur',
	'id_ligne',
	'time_day',
	'place',
	'description',
	'pertes',
	'photos',
    ];
    protected $hidden = ['created_at', 'updated_at'];

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

}
