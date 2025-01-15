<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class traveauxlibreusedpieces extends Model
{
    protected $table = 'traveauxlibreusedpieces';

    protected $hidden = ['created_at', 'updated_at'];


    protected $fillable  = ['traveauxlibre_id', 'piece_id','quantité'];
    public function traveauxlibre(): BelongsTo
    {
        return $this->belongsTo(traveauxlibre_model::class, 'traveauxlibre_id');
    }

    /**
     * Get the piece associated with the used piece.
     */
    public function piece(): BelongsTo
    {
        return $this->belongsTo(pieces_maintanance::class, 'piece_id');
    }
}
