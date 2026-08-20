<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = [
        'id_liquidacion',
        'id_autorizacion',
        'id_usuario_registrador',
        'fecha_pago',
        'monto',
        'metodo_pago',
        'referencia_pago',
        'observacion',
    ];

    /**
     * Relationship to Liquidacion.
     */
    public function liquidacion(): BelongsTo
    {
        return $this->belongsTo(Liquidacion::class, 'id_liquidacion', 'id_liquidacion');
    }

    /**
     * Relationship to User (registrador).
     */
    public function registrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_registrador', 'id_usuario');
    }

    /**
     * Formatted amount in COP ($100,000).
     */
    public function getFormattedMontoAttribute(): string
    {
        $val = (float)($this->monto ?? 0);
        return '$' . number_format($val, 0, '.', ',');
    }

    /**
     * Formatted payment date YYYY-MM-DD.
     */
    public function getFormattedFechaPagoAttribute(): string
    {
        if (!$this->fecha_pago) {
            return '-';
        }
        try {
            return Carbon::parse($this->fecha_pago)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->fecha_pago;
        }
    }

    /**
     * Liquidation code e.g. LIQ-002.
     */
    public function getLiquidacionCodigoAttribute(): string
    {
        return 'LIQ-' . str_pad($this->id_liquidacion ?? 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Worker name from liquidation.
     */
    public function getTrabajadorNombreAttribute(): string
    {
        return $this->liquidacion?->trabajador?->usuario?->name ?? 'Trabajador';
    }

    /**
     * Worker document from liquidation.
     */
    public function getTrabajadorDocumentoAttribute(): string
    {
        return $this->liquidacion?->trabajador?->usuario?->documento ?? '-';
    }
}
