<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'cliente_nombre',
        'cliente_identificacion_tipo',
        'cliente_identificacion',
        'cliente_email',
        'vendedor_id',
        'monto_total'
    ];

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }
}
