<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificacionOperativa extends Model
{
    use HasFactory;

    protected $table = 'notificaciones_operativas';
    protected $primaryKey = 'id_notificacion';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario_destino',
        'tipo',
        'mensaje',
        'link',
        'fecha_hora',
        'leida',
    ];

    public function usuarioDestino(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_destino', 'id_usuario');
    }

    public function getTiempoRelativoAttribute(): string
    {
        if (!$this->fecha_hora) return '';
        try {
            return Carbon::parse($this->fecha_hora)->diffForHumans();
        } catch (\Exception $e) {
            return $this->fecha_hora;
        }
    }
}
