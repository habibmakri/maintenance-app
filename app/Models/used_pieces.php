<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class used_pieces extends Model
{
    protected $table = 'used_pieces';

    protected $hidden = ['created_at', 'updated_at'];


    protected $fillable  = ['fichepanne_id', 'piece_id','quantité'];
    public function fichePanne(): BelongsTo
    {
        return $this->belongsTo(fichepanne_model::class, 'fichepanne_id');
    }

    /**
     * Get the piece associated with the used piece.
     */
    public function piece(): BelongsTo
    {
        return $this->belongsTo(pieces_maintanance::class, 'piece_id');
    }
}
