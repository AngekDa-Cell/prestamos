<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['nombre', 'apellidoP', 'apellidoM', 'fecha_ingreso'];
    public $timestamps = false;

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'fk_id_empleado');
    }

    public function puestos()
    {
        return $this->belongsToMany(Puesto::class, 'det_emp_puesto', 'fk_id_empleado', 'fk_id_puesto')
                    ->withPivot('fecha_inicio', 'fecha_fin');
    }
}
