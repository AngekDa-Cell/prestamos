<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;

    protected $table = 'abonos';
    protected $primaryKey = 'id_abono';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'fk_id_prestamo',
        'num_abono',
        'fecha',
        'monto_capital',
        'monto_interes',
        'monto_cobrado',
        'saldo_pendiente'
    ];
    public $timestamps = false;

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'fk_id_prestamo');
    }
}
