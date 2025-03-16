<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class traveauxlibre_model extends Model
{
    use HasFactory;

    protected $table = 'traveauxlibre'; 
    
    protected $fillable = ['name','id_bus','nature','date_resoudre','lieu_resoudre','brigade','grantraveaux','equipe','delai','description'];
    protected $hidden = ['created_at', 'updated_at'];
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'id_bus');
    }
    public function used_pieces(){
        return $this->hasMany(traveauxlibreusedpieces::class,'traveauxlibre_id');
    }
    
}
