<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ctechnique_clients extends Model
{
    protected $table = 'ctechniqueclients';

    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'date_controle',
        'name',
        'type_id',
        'immatriculation',
        'phone',
        'last_remind',
    ];
    public function type()
    {
        return $this->belongsTo(ctechniqueclienttypes::class, 'type_id');
    }
}
