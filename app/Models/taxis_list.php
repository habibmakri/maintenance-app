<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class taxis_list extends Model
{
    protected $table = 'taxis_list';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped (created_at and updated_at).
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'counter',
        'valid_date',
    ];

    public function count_taxis(): HasMany
    {
        return $this->hasMany(taxis::class, 'list');
    }
}
