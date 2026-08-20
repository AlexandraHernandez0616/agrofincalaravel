<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $table = 'insumos';
    protected $primaryKey = 'id_insumo';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'stock_actual',
        'unidad_medida',
        'fecha_vencimiento',
        'cantidad_minima',
        'foto_referencia',
        'fecha_registro',
    ];

    /**
     * Get image url or placeholder.
     */
    public function getFotoUrlAttribute(): string
    {
        if (!empty($this->foto_referencia)) {
            if (filter_var($this->foto_referencia, FILTER_VALIDATE_URL)) {
                return $this->foto_referencia;
            }
            if (file_exists(public_path($this->foto_referencia))) {
                return asset($this->foto_referencia);
            }
            if (file_exists(public_path('storage/' . $this->foto_referencia))) {
                return asset('storage/' . $this->foto_referencia);
            }
        }

        return asset('img/default_supply.svg');
    }

    /**
     * Calculate health status for the supply based on stock and expiration.
     */
    public function getEstadoCalculadoAttribute(): string
    {
        if ($this->fecha_vencimiento && Carbon::parse($this->fecha_vencimiento)->isPast()) {
            return 'Vencido';
        }

        if ($this->stock_actual <= $this->cantidad_minima) {
            return 'Bajo Stock';
        }

        return 'Normal';
    }

    /**
     * Formatted expiration date (YYYY-MM-DD).
     */
    public function getFechaVencimientoDateAttribute(): ?string
    {
        if (!$this->fecha_vencimiento) {
            return null;
        }
        try {
            return Carbon::parse($this->fecha_vencimiento)->format('Y-m-d');
        } catch (\Exception $e) {
            return substr((string)$this->fecha_vencimiento, 0, 10);
        }
    }

    /**
     * Formatted registration date (YYYY-MM-DD).
     */
    public function getFechaRegistroDateAttribute(): string
    {
        if (!$this->fecha_registro) {
            return date('Y-m-d');
        }
        try {
            return Carbon::parse($this->fecha_registro)->format('Y-m-d');
        } catch (\Exception $e) {
            return substr((string)$this->fecha_registro, 0, 10);
        }
    }
}
