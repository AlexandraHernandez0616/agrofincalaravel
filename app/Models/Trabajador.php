<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trabajador extends Model
{
    use HasFactory;

    protected $table = 'trabajadores';
    protected $primaryKey = 'id_trabajador';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_usuario',
        'eps',
        'rh',
        'estado_trabajador',
        'fecha_ingreso',
        'hora_registro',
    ];

    /**
     * Relationship to the user account in usuarios table.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Alias for usuario relationship.
     */
    public function user(): BelongsTo
    {
        return $this->usuario();
    }

    /**
     * Get worker's first names directly from usuario.
     */
    public function getNombresAttribute(): ?string
    {
        return $this->usuario?->nombres ?? '';
    }

    /**
     * Get worker's last names directly from usuario.
     */
    public function getApellidosAttribute(): ?string
    {
        return $this->usuario?->apellidos ?? '';
    }

    /**
     * Get worker's full name.
     */
    public function getNameAttribute(): string
    {
        return $this->usuario?->name ?? ('Trabajador #' . $this->id_trabajador);
    }

    /**
     * Get worker's document directly from usuario.
     */
    public function getDocumentoAttribute(): ?string
    {
        return $this->usuario?->documento ?? '';
    }

    /**
     * Get worker's phone directly from usuario.
     */
    public function getTelefonoAttribute(): ?string
    {
        return $this->usuario?->telefono ?? '';
    }

    /**
     * Get worker's username directly from usuario.
     */
    public function getUsernameAttribute(): ?string
    {
        return $this->usuario?->username ?? '';
    }

    /**
     * Get worker's initials.
     */
    public function getInitialsAttribute(): string
    {
        return $this->usuario?->initials ?? 'TR';
    }

    /**
     * Check if worker is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return strtoupper($this->estado_trabajador ?? 'ACTIVO') === 'ACTIVO';
    }

    /**
     * Formatted date for registration (YYYY-MM-DD).
     */
    public function getFechaRegistroDateAttribute(): string
    {
        $date = $this->fecha_ingreso ?? $this->hora_registro ?? $this->usuario?->fecha_creacion;
        if (!$date) {
            return date('Y-m-d');
        }
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return substr((string)$date, 0, 10);
        }
    }

    /**
     * Formatted date with full spanish month.
     */
    public function getFormattedFechaRegistroAttribute(): string
    {
        $date = $this->fecha_ingreso ?? $this->hora_registro ?? $this->usuario?->fecha_creacion;
        if (!$date) {
            return 'No registrada';
        }
        try {
            return Carbon::parse($date)->translatedFormat('d \d\e F, Y');
        } catch (\Exception $e) {
            return (string)$date;
        }
    }
}
