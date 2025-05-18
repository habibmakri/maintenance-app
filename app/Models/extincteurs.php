<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class extincteurs extends Model
{
    protected $table = 'extincteur';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped (created_at and updated_at).
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'reference',
        'type',
        'bus',
        'affectation',
        'date_recharge',
        'date_expiration',
    ];
}
