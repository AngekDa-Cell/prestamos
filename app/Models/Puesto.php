<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;

    protected $table = 'puestos';
    protected $primaryKey = 'id_puesto';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['nombre', 'sueldo'];
    public $timestamps = false;

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'det_emp_puesto', 'fk_id_puesto', 'fk_id_empleado')
                    ->withPivot('fecha_inicio', 'fecha_fin');
    }
}
