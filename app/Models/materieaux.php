<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class materieaux extends Model
{
    protected $table = 'materiels';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped (created_at and updated_at).
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'numinventaire',
        'numinventaire',
    ];

}
