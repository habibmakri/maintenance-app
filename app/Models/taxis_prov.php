<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class taxis_prov extends Model
{
    use SoftDeletes;

    protected $table = 'taxis_prov';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped (created_at and updated_at).
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'nin',
        'inscription_time',
        'gender',
        'nom_ar',
        'prenom_ar',
        'nom_fr',
        'prenom_fr',
        'birthdate',
        'birthplace',
        'adresse',
        'phone',
        'email',
        'n_permis',
        'date_permis',
        'lieu_permis',
        'comune_exploi',
        'rejet',
        'validation_number',
        'list',
        'ip_adress',
    ];

    // public function list()
    // {
    //     return $this->belongsTo(Panne::class, 'pannnename_id');
    // }
}
