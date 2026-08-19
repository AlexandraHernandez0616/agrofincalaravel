<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
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
        'username',
        'password_hash',
        'rol',
        'activo',
        'fecha_creacion',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get user full name.
     */
    public function getNameAttribute()
    {
        return trim(($this->nombres ?? '') . ' ' . ($this->apellidos ?? '')) ?: ($this->username ?? 'Usuario');
    }

    /**
     * Get user full name.
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get user avatar initials.
     */
    public function getInitialsAttribute()
    {
        $first = mb_substr($this->nombres ?? $this->username ?? 'A', 0, 1);
        $last = mb_substr($this->apellidos ?? '', 0, 1);
        return strtoupper($first . $last);
    }

    /**
     * Get formatted creation date.
     */
    public function getFormattedFechaCreacionAttribute()
    {
        if (!$this->fecha_creacion) {
            return 'No registrada';
        }
        try {
            return \Carbon\Carbon::parse($this->fecha_creacion)->translatedFormat('d \d\e F, Y');
        } catch (\Exception $e) {
            return $this->fecha_creacion;
        }
    }

    /**
     * Get simple creation date (YYYY-MM-DD).
     */
    public function getFechaCreacionDateAttribute()
    {
        if (!$this->fecha_creacion) {
            return date('Y-m-d');
        }
        try {
            return \Carbon\Carbon::parse($this->fecha_creacion)->format('Y-m-d');
        } catch (\Exception $e) {
            return substr($this->fecha_creacion, 0, 10);
        }
    }
}
