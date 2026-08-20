<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produccion extends Model
{
    use HasFactory;

    protected $table = 'producciones';
    protected $primaryKey = 'id_produccion';
    public $timestamps = false;

    protected $fillable = [
        'id_trabajador',
        'id_lote',
        'fecha',
        'cantidad',
        'unidad_medida',
    ];

    /**
     * Relationship to Lote.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    /**
     * Relationship to Trabajador who logged the harvest.
     */
    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'id_trabajador', 'id_trabajador');
    }

    /**
     * Get formatted date.
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
}
