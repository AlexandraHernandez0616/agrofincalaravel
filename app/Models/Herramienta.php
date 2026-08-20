<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Herramienta extends Model
{
    use HasFactory;

    protected $table = 'herramientas';
    protected $primaryKey = 'id_herramienta';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'cantidad_total',
        'estado',
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

        return asset('img/default_tool.svg');
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
