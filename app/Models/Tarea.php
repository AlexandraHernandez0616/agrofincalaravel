<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';
    protected $primaryKey = 'id_tarea';
    public $timestamps = false;

    protected $fillable = [
        'id_lote',
        'id_mayordomo',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin_estimada',
        'estado_tarea',
    ];

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    public function mayordomo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mayordomo', 'id_usuario');
    }

    public function getFormattedFechaInicioAttribute(): string
    {
        if (!$this->fecha_inicio) return '-';
        try {
            return Carbon::parse($this->fecha_inicio)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->fecha_inicio;
        }
    }
}
