<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = \App\Models\User::where('username', $this->input('username'))->first();

        // 1. Si el usuario existe en la tabla usuarios pero está inactivo
        if ($user && ! $user->activo) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'username' => 'Tu cuenta se encuentra inactiva. Por favor contacta al Mayordomo o Administrador.',
            ]);
        }

        // 2. Si no existe en usuarios, verificar si tiene una solicitud en solicitudes_registros
        if (! $user) {
            $solicitud = \App\Models\SolicitudRegistro::where('username', $this->input('username'))
                ->orWhere('documento', $this->input('username'))
                ->latest('id_solicitud')
                ->first();

            if ($solicitud) {
                RateLimiter::hit($this->throttleKey());
                if ($solicitud->estado === 'PENDIENTE') {
                    throw ValidationException::withMessages([
                        'username' => 'Tu solicitud de acceso como trabajador aún está pendiente de aprobación por el Mayordomo.',
                    ]);
                } elseif ($solicitud->estado === 'RECHAZADA') {
                    $obs = $solicitud->observacion ? " Motivo: {$solicitud->observacion}" : '';
                    throw ValidationException::withMessages([
                        'username' => "Tu solicitud de acceso fue rechazada por el Mayordomo.{$obs}",
                    ]);
                }
            }
        }

        if (! Auth::attempt($this->only('username', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => 'Las credenciales ingresadas no son correctas.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username')).'|'.$this->ip());
    }
}
