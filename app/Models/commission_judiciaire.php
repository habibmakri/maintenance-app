<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class commission_judiciaire extends Model
{
    protected $table = 'commission_judiciaire'; 
    
    protected $fillable = ['date','time','number','members'];
    protected $hidden = ['created_at', 'updated_at'];

    public function used_pieces(){
        return $this->hasMany(declaration_judiciaire::class,'commission_id');
    }
    
}
