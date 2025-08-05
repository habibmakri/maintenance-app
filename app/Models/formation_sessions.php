<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class formation_sessions extends Model
{
    protected $table = 'formation_sessions';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped (created_at and updated_at).
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'type',
        'date_debut',
        'date_fin',
        'counter',
        'profs',
        'valid_date',
    ];
    protected $modeltypes = [
        'taxis' => \App\Models\taxis::class,
        'tper' => \App\Models\tper::class,
        'tmar' => \App\Models\tmar::class,
        'tdan' => \App\Models\tdan::class,
    ];
    public function count_models($type): HasMany
    {
        $model = $this->modeltypes[$type] ?? null;

        if (!$model) {
            throw new \Exception("Erreur unkown: " . $type);
        }

        return $this->hasMany($model, 'session_id');
    }
}
