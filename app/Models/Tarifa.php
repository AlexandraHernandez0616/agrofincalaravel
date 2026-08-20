<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    use HasFactory;

    protected $table = 'tarifas';
    protected $primaryKey = 'id_tarifa';
    public $timestamps = false;

    protected $fillable = [
        'tipo_pago',
        'valor',
        'fecha_inicio_vigencia',
        'fecha_fin_vigencia',
        'activa',
    ];

    /**
     * Format currency in COP style: $12,000 or $50,000.
     */
    public function getFormattedValorAttribute(): string
    {
        $val = (float)($this->valor ?? 0);
        return '$' . number_format($val, 0, '.', ',');
    }

    /**
     * Format start date.
     */
    public function getFormattedFechaInicioAttribute(): string
    {
        if (!$this->fecha_inicio_vigencia) {
            return '-';
        }
        try {
            return Carbon::parse($this->fecha_inicio_vigencia)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->fecha_inicio_vigencia;
        }
    }

    /**
     * Format end date.
     */
    public function getFormattedFechaFinAttribute(): string
    {
        if (!$this->fecha_fin_vigencia) {
            return '-';
        }
        try {
            return Carbon::parse($this->fecha_fin_vigencia)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->fecha_fin_vigencia;
        }
    }

    /**
     * Check if tariff is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return (bool) $this->activa;
    }

    /**
     * Get label for status.
     */
    public function getEstadoLabelAttribute(): string
    {
        return $this->is_active ? 'Activa' : 'Inactiva';
    }
}
