<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutorizacionDelegada extends Model
{
    use HasFactory;

    protected $table = 'autorizaciones_delegadas';
    protected $primaryKey = 'id_autorizacion';
    public $timestamps = false;

    protected $fillable = [
        'id_administrador',
        'id_mayordomo',
        'fecha_inicio',
        'fecha_fin',
        'acciones_permitidas',
        'monto_maximo',
        'estado',
    ];

    /**
     * Relationship to Administrador (User).
     */
    public function administrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_administrador', 'id_usuario');
    }

    /**
     * Relationship to Mayordomo (User).
     */
    public function mayordomo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mayordomo', 'id_usuario');
    }

    /**
     * Relationship to Liquidaciones performed under this authorization.
     */
    public function liquidaciones(): HasMany
    {
        return $this->hasMany(Liquidacion::class, 'id_autorizacion', 'id_autorizacion');
    }

    /**
     * Relationship to Pagos performed under this authorization.
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_autorizacion', 'id_autorizacion');
    }

    /**
     * Formatted start date YYYY-MM-DD.
     */
    public function getFormattedFechaInicioAttribute(): string
    {
        if (!$this->fecha_inicio) {
            return '-';
        }
        try {
            return Carbon::parse($this->fecha_inicio)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->fecha_inicio;
        }
    }

    /**
     * Formatted end date YYYY-MM-DD.
     */
    public function getFormattedFechaFinAttribute(): string
    {
        if (!$this->fecha_fin) {
            return '-';
        }
        try {
            return Carbon::parse($this->fecha_fin)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->fecha_fin;
        }
    }

    /**
     * Get mayordomo full name.
     */
    public function getMayordomoNombreAttribute(): string
    {
        return $this->mayordomo?->name ?? 'Mayordomo';
    }

    /**
     * Get administrator full name.
     */
    public function getAdministradorNombreAttribute(): string
    {
        return $this->administrador?->name ?? 'Admin Principal';
    }

    /**
     * Formatted max amount in COP.
     */
    public function getFormattedMontoMaximoAttribute(): string
    {
        if (!$this->monto_maximo || $this->monto_maximo <= 0) {
            return 'Sin límite';
        }
        return '$' . number_format((float)$this->monto_maximo, 0, '.', ',');
    }
}
