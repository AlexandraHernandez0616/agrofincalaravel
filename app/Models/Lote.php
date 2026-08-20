<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';
    protected $primaryKey = 'id_lote';
    public $timestamps = false;

    protected $fillable = [
        'id_cultivo',
        'nombre',
        'ubicacion_descripcion',
        'extension',
        'fecha_registro',
    ];

    /**
     * Relationship to the cultivated crop.
     */
    public function cultivo(): BelongsTo
    {
        return $this->belongsTo(Cultivo::class, 'id_cultivo', 'id_cultivo');
    }

    /**
     * Relationship to production harvest logs.
     */
    public function producciones(): HasMany
    {
        return $this->hasMany(Produccion::class, 'id_lote', 'id_lote');
    }

    /**
     * Get total production sum formatted with unit (e.g., '0 kg', '150.50 kg').
     */
    public function getProduccionTotalAttribute(): string
    {
        $sum = $this->producciones->sum('cantidad');
        $unidad = $this->producciones->first()->unidad_medida ?? 'kg';
        return ((float)$sum == (int)$sum ? (int)$sum : number_format((float)$sum, 2)) . ' ' . $unidad;
    }

    /**
     * Get numeric production total.
     */
    public function getProduccionTotalNumAttribute(): float
    {
        return (float) $this->producciones->sum('cantidad');
    }

    /**
     * Get formatted extension (e.g., '5.00 hectáreas').
     */
    public function getFormattedExtensionAttribute(): string
    {
        $ext = (float)($this->extension ?? 0);
        return number_format($ext, 2) . ' hectáreas';
    }

    /**
     * Get formatted registration date (YYYY-MM-DD).
     */
    public function getFormattedFechaRegistroAttribute(): string
    {
        if (!$this->fecha_registro) {
            return '-';
        }
        try {
            return Carbon::parse($this->fecha_registro)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->fecha_registro;
        }
    }

    /**
     * Get crop type name in lowercase for pills (e.g., 'cafe', 'cacao').
     */
    public function getTipoCultivoSlugAttribute(): string
    {
        return strtolower(trim($this->cultivo?->nombre ?? 'general'));
    }
}
