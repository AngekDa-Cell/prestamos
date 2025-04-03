<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetEmpPuesto extends Model
{
    use HasFactory;

    protected $table = 'det_emp_puesto';
    protected $primaryKey = 'id_det_emp_puesto';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'fk_id_empleado',
        'fk_id_puesto',
        'fecha_inicio',
        'fecha_fin'
    ];
    public $timestamps = false;

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'fk_id_empleado');
    }

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'fk_id_puesto');
    }
}
