<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class emplacements extends Model
{
    protected $table = 'emplacements';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped (created_at and updated_at).
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'nom',
    ];


      public function count_materieaux(): HasMany
    {
        return $this->hasMany(materieaux::class,'id_emplacements');
    }
}
