<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class gasoile_transactions extends Model
{
    protected $table = 'gasoile_transactions';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped (created_at and updated_at).
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];
 
    protected $fillable = [
        'id_carte',
        'recharge',
        'chauffeur',
        'mission',
        'place',
        'date',
        'quantite',
    ];

    public function carte()
    {
        return $this->belongsTo(gasoile_cartes::class, 'id_carte', 'id');
    }
}
