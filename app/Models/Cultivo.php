<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cultivo extends Model
{
    use HasFactory;

    protected $table = 'cultivos';
    protected $primaryKey = 'id_cultivo';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'variedad',
        'cantidad_cultivo',
        'fecha_registro',
        'estado',
    ];

    /**
     * Relationship to Lotes.
     */
    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class, 'id_cultivo', 'id_cultivo');
    }

    /**
     * Format crop quantity with units.
     */
    public function getFormattedCantidadAttribute(): string
    {
        return number_format((float)($this->cantidad_cultivo ?? 0), 2);
    }
}
