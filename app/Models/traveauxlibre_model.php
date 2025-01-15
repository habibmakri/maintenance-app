<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class traveauxlibre_model extends Model
{
    use HasFactory;

    protected $table = 'traveauxlibre'; 
    
    protected $fillable = ['name','id_bus','nature','date_resoudre','lieu_resoudre','brigade','equipe','description'];
    protected $hidden = ['created_at', 'updated_at'];
    public function bus()
    {
        return $this->belongsTo(bus::class, 'id_bus');
    }
}
