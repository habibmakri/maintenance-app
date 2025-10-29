<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class entreprise extends Authenticatable
{
    protected $table = 'entreprise';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped (created_at and updated_at).
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'name',
        'activity',
        'gerant',
        'adresse',
        'waiting_status',
        'phone',
        'email',
        'payments',
        'password',
        'nrc',
        'nif',
        'nis',
    ];
    public function count_tper_emps(): HasMany
    {
        return $this->hasMany(tper::class, 'entreprise_id');
    }
    public function count_tmar_emps(): HasMany
    {
        return $this->hasMany(tmar::class, 'entreprise_id');
    }
    public function count_tdan_emps(): HasMany
    {
        return $this->hasMany(tdan::class, 'entreprise_id');
    }
    public function getTotalEmps()
    {
        return $this->count_tper_emps->count()
            + $this->count_tmar_emps->count()
            + $this->count_tdan_emps->count();
    }

    public function getNonPaidEmps()
    {
        $tper = $this->count_tper_emps->whereNotNull('payment_number')->count();
        $tmar = $this->count_tmar_emps->whereNotNull('payment_number')->count();
        $tdan = $this->count_tdan_emps->whereNotNull('payment_number')->count();

        return $tper + $tmar + $tdan;
    }
}
