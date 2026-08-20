<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';
    protected $primaryKey = 'id_prestamo';
    public $timestamps = false;

    protected $fillable = [
        'id_trabajador',
        'id_mayordomo',
        'fecha_solicitud',
        'fecha_aprobacion',
        'estado_prestamo',
        'observacion',
    ];

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'id_trabajador', 'id_trabajador');
    }

    public function mayordomo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mayordomo', 'id_usuario');
    }

    public function getFormattedFechaSolicitudAttribute(): string
    {
        if (!$this->fecha_solicitud) return '-';
        try {
            return Carbon::parse($this->fecha_solicitud)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->fecha_solicitud;
        }
    }
}
