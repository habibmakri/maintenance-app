<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class tdan extends Model
{
      use SoftDeletes;

    protected $table = 'transport_danger';

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
        'type_permis',
        'date_permis',
        'lieu_permis',
        'rejet',
        'validation_number',
        'payment_number',
        'entreprise_id',
        'ip_adress',
        'date_paiement',
    ];

    
}
