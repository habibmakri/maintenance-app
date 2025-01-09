<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fichepanne_model extends Model
{
    use HasFactory;

    protected $table = 'fichepanne'; // Specify the table name if it's not the default plural form

    // Add fillable fields
    protected $fillable = ['fichemaintenance_id','pannnename_id','solved','date_resoudre','lieu_resoudre','brigade','equipe','description'];
    protected $hidden = ['created_at', 'updated_at'];
    public function fichemaintenance()
    {
        return $this->belongsTo(fichemaintenance::class, 'fichemaintenance_id');
    }
    public function pannename()
    {
        return $this->belongsTo(Panne::class, 'pannnename_id');
    }
    public function used_pieces(){
        return $this->hasMany(used_pieces::class,'fichepanne_id');
    }
}
