<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class autoecole extends Model
{
    use SoftDeletes;

    protected $table = 'autoecole';

    protected $primaryKey = 'id';

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
        'rejet',
        'list',
        'validation_number',
        'payment_number',
        'cheque_number',
        'date_paiement',
        'montant_paiement',
        'notes',
        'type',
        'session_id',
    ];
    public function list_m(): BelongsTo
    {
        return $this->belongsTo(autoecole_list::class, 'list');
    }

}
