<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class counters_formation extends Model
{
    protected $table = 'counters_formation';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'type',	
        'last_number',	
        'date',
        'detail'
    ];

    // public function validation_number(): BelongsTo
    // {
    //     return $this->belongsTo(tdan::class, 'validation_number');
    // }
    // public function payment_number(): BelongsTo
    // {
    //     return $this->belongsTo(tmar::class, 'payment_number');
    // }





}
