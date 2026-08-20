<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Liquidacion extends Model
{
    use HasFactory;

    protected $table = 'liquidaciones';
    protected $primaryKey = 'id_liquidacion';
    public $timestamps = false;

    protected $fillable = [
        'id_trabajador',
        'id_tarifa',
        'id_autorizacion',
        'periodo_inicio',
        'periodo_fin',
        'jornadas_consideradas',
        'produccion_considerada',
        'valor_calculado',
        'fecha_generacion',
        'fecha_liquidacion',
        'estado',
        'observacion',
    ];

    /**
     * Relationship to Trabajador.
     */
    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'id_trabajador', 'id_trabajador');
    }

    /**
     * Relationship to Tarifa.
     */
    public function tarifa(): BelongsTo
    {
        return $this->belongsTo(Tarifa::class, 'id_tarifa', 'id_tarifa');
    }

    /**
     * Format calculated amount in COP ($100,000).
     */
    public function getFormattedValorAttribute(): string
    {
        $val = (float)($this->valor_calculado ?? 0);
        return '$' . number_format($val, 0, '.', ',');
    }

    /**
     * Format start date.
     */
    public function getFormattedPeriodoInicioAttribute(): string
    {
        if (!$this->periodo_inicio) {
            return '-';
        }
        try {
            return Carbon::parse($this->periodo_inicio)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->periodo_inicio;
        }
    }

    /**
     * Format end date.
     */
    public function getFormattedPeriodoFinAttribute(): string
    {
        if (!$this->periodo_fin) {
            return '-';
        }
        try {
            return Carbon::parse($this->periodo_fin)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->periodo_fin;
        }
    }

    /**
     * Format jornadas count.
     */
    public function getJornadasFormattedAttribute(): string
    {
        $val = (float)($this->jornadas_consideradas ?? 0);
        return (string) ((int)$val == $val ? (int)$val : number_format($val, 1));
    }

    /**
     * Get worker full name.
     */
    public function getTrabajadorNombreAttribute(): string
    {
        return $this->trabajador?->usuario?->name ?? 'Trabajador';
    }

    /**
     * Get tariff type name.
     */
    public function getTipoTarifaNombreAttribute(): string
    {
        return $this->tarifa?->tipo_pago ?? 'General';
    }
}
