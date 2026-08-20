<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencia';
    public $timestamps = false;

    protected $fillable = [
        'id_trabajador',
        'fecha',
        'hora_entrada',
        'hora_salida',
    ];

    /**
     * Relationship to Trabajador.
     */
    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'id_trabajador', 'id_trabajador');
    }

    /**
     * Formatted date YYYY-MM-DD.
     */
    public function getFormattedFechaAttribute(): string
    {
        if (!$this->fecha) {
            return '-';
        }
        try {
            return Carbon::parse($this->fecha)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->fecha;
        }
    }

    /**
     * Formatted entrance time HH:mm.
     */
    public function getFormattedHoraEntradaAttribute(): string
    {
        if (!$this->hora_entrada) {
            return '—';
        }
        try {
            return Carbon::parse($this->hora_entrada)->format('H:i');
        } catch (\Exception $e) {
            return $this->hora_entrada;
        }
    }

    /**
     * Formatted exit time HH:mm.
     */
    public function getFormattedHoraSalidaAttribute(): string
    {
        if (!$this->hora_salida) {
            return '—';
        }
        try {
            return Carbon::parse($this->hora_salida)->format('H:i');
        } catch (\Exception $e) {
            return $this->hora_salida;
        }
    }

    /**
     * Calculate worked hours string (e.g. 8 hrs, 0 hrs, or —).
     */
    public function getTotalHorasAttribute(): string
    {
        if (!$this->hora_entrada || !$this->hora_salida) {
            return '—';
        }
        try {
            $in = Carbon::parse($this->hora_entrada);
            $out = Carbon::parse($this->hora_salida);
            $diffHours = $in->diffInHours($out);
            return $diffHours . ' hrs';
        } catch (\Exception $e) {
            return '—';
        }
    }

    /**
     * Worker full name.
     */
    public function getTrabajadorNombreAttribute(): string
    {
        return $this->trabajador?->usuario?->name ?? 'Trabajador';
    }
}
