<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class chauffeurs extends Model
{
    protected $table = 'chauffeurs';

    protected $hidden = ['created_at', 'updated_at'];

    // Add fillable fields
    protected $fillable = ['name', 'fr_name', 'matricule'];
    public function declarations()
    {
        return $this->hasMany(declaration_judiciaire::class, 'id_chauffeur', 'id');
    }
}
