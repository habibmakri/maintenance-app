<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class gasoile_cartes extends Model
{
     protected $table = 'gasoile_cartes';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped (created_at and updated_at).
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'number',
        'name',
        'initial_balance',
        'actual_balance',
        'state',
    ];
}
