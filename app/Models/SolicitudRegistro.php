<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudRegistro extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_registros';
    protected $primaryKey = 'id_solicitud';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombres',
        'apellidos',
        'documento',
        'telefono',
        'eps',
        'rh',
        'username',
        'password_hash',
        'estado',
        'fecha_solicitud',
        'fecha_gestion',
        'id_mayordomo',
        'observacion',
    ];

    /**
     * Mayordomo who reviewed this request.
     */
    public function mayordomo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mayordomo', 'id_usuario');
    }

    /**
     * Full name of applicant.
     */
    public function getNameAttribute(): string
    {
        return trim(($this->nombres ?? '') . ' ' . ($this->apellidos ?? '')) ?: $this->username;
    }

    /**
     * Formatted application date.
     */
    public function getFormattedFechaSolicitudAttribute(): string
    {
        if (!$this->fecha_solicitud) {
            return 'No registrada';
        }
        try {
            return Carbon::parse($this->fecha_solicitud)->translatedFormat('d \d\e F, Y - h:i A');
        } catch (\Exception $e) {
            return (string)$this->fecha_solicitud;
        }
    }
}
