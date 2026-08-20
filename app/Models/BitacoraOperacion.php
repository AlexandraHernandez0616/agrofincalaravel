<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class BitacoraOperacion extends Model
{
    use HasFactory;

    protected $table = 'bitacora_operacion';
    protected $primaryKey = 'id_bitacora';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'fecha_hora',
        'operacion',
        'modulo',
        'detalle',
    ];

    /**
     * Relationship to User (Usuario).
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Formatted date YYYY-MM-DD.
     */
    public function getFormattedFechaAttribute(): string
    {
        if (!$this->fecha_hora) {
            return '-';
        }
        try {
            return Carbon::parse($this->fecha_hora)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->fecha_hora;
        }
    }

    /**
     * Formatted time HH:mm:ss.
     */
    public function getFormattedHoraAttribute(): string
    {
        if (!$this->fecha_hora) {
            return '-';
        }
        try {
            return Carbon::parse($this->fecha_hora)->format('H:i:s');
        } catch (\Exception $e) {
            return '-';
        }
    }

    /**
     * User full name.
     */
    public function getUsuarioNombreAttribute(): string
    {
        return $this->usuario?->name ?? 'Sistema';
    }

    /**
     * User role.
     */
    public function getUsuarioRolAttribute(): string
    {
        return $this->usuario?->rol ?? 'SISTEMA';
    }

    /**
     * Helper to log an event into the bitácora.
     */
    public static function log(string $operacion, string $modulo, ?string $detalle = null, ?int $idUsuario = null): self
    {
        return self::create([
            'id_usuario' => $idUsuario ?? (Auth::id() ?? 1),
            'fecha_hora' => now(),
            'operacion' => $operacion,
            'modulo' => $modulo,
            'detalle' => $detalle,
        ]);
    }
}
