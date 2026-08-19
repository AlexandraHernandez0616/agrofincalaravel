<div class="profile-section-card">
  <div class="profile-card-header">
    <div class="profile-card-title-box">
      <div class="profile-card-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </div>
      <div>
        <h2 class="profile-card-title">Información Personal y de Cuenta</h2>
        <p class="profile-card-desc">Actualiza tus datos básicos de identificación y contacto en AgroFinca.</p>
      </div>
    </div>
  </div>

  @if (session('status') === 'profile-updated')
    <div class="af-alert success">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      <span>Tus datos de perfil han sido actualizados exitosamente.</span>
    </div>
  @endif

  <form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')

    <div class="form-grid-2">
      <!-- Nombres -->
      <div class="form-group-af">
        <label for="nombres" class="form-label-af">Nombres <span style="color: #ef4444;">*</span></label>
        <div class="af-input-wrapper">
          <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <input 
            type="text" 
            id="nombres" 
            name="nombres" 
            class="af-form-input @error('nombres') is-invalid @enderror" 
            value="{{ old('nombres', $user->nombres) }}" 
            placeholder="Ej. Juan Carlos" 
            required 
            autocomplete="given-name"
          />
        </div>
        @error('nombres')
          <div class="form-field-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ $message }}
          </div>
        @enderror
      </div>

      <!-- Apellidos -->
      <div class="form-group-af">
        <label for="apellidos" class="form-label-af">Apellidos <span style="color: #ef4444;">*</span></label>
        <div class="af-input-wrapper">
          <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <input 
            type="text" 
            id="apellidos" 
            name="apellidos" 
            class="af-form-input @error('apellidos') is-invalid @enderror" 
            value="{{ old('apellidos', $user->apellidos) }}" 
            placeholder="Ej. Pérez Gómez" 
            required 
            autocomplete="family-name"
          />
        </div>
        @error('apellidos')
          <div class="form-field-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ $message }}
          </div>
        @enderror
      </div>

      <!-- Documento -->
      <div class="form-group-af">
        <label for="documento" class="form-label-af">Documento de Identidad <span style="color: #ef4444;">*</span></label>
        <div class="af-input-wrapper">
          <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
          <input 
            type="text" 
            id="documento" 
            name="documento" 
            class="af-form-input @error('documento') is-invalid @enderror" 
            value="{{ old('documento', $user->documento) }}" 
            placeholder="Ej. 1098765432" 
            required 
          />
        </div>
        @error('documento')
          <div class="form-field-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ $message }}
          </div>
        @enderror
      </div>

      <!-- Teléfono -->
      <div class="form-group-af">
        <label for="telefono" class="form-label-af">
          Teléfono de Contacto 
          <span class="form-label-optional">(Opcional)</span>
        </label>
        <div class="af-input-wrapper">
          <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <input 
            type="text" 
            id="telefono" 
            name="telefono" 
            class="af-form-input @error('telefono') is-invalid @enderror" 
            value="{{ old('telefono', $user->telefono) }}" 
            placeholder="Ej. +57 300 123 4567" 
          />
        </div>
        @error('telefono')
          <div class="form-field-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ $message }}
          </div>
        @enderror
      </div>

      <!-- Nombre de Usuario -->
      <div class="form-group-af">
        <label for="username" class="form-label-af">Usuario de Acceso <span style="color: #ef4444;">*</span></label>
        <div class="af-input-wrapper">
          <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
          <input 
            type="text" 
            id="username" 
            name="username" 
            class="af-form-input @error('username') is-invalid @enderror" 
            value="{{ old('username', $user->username) }}" 
            placeholder="Ej. jperez" 
            required 
            autocomplete="username"
          />
        </div>
        @error('username')
          <div class="form-field-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ $message }}
          </div>
        @enderror
      </div>

      <!-- Rol Asignado (Solo Lectura) -->
      <div class="form-group-af">
        <label class="form-label-af">
          Rol en el Sistema 
          <span class="form-label-optional">(Asignado por Administrador)</span>
        </label>
        <div class="af-input-wrapper">
          <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <input 
            type="text" 
            class="af-form-input" 
            value="{{ $user->rol ?? 'ADMINISTRADOR' }}" 
            readonly 
            disabled 
          />
        </div>
        <div class="form-field-hint">
          Los permisos del rol definen los módulos a los que tienes acceso.
        </div>
      </div>
    </div>

    <!-- Footer / Botón Guardar -->
    <div class="profile-form-footer">
      <div>
        @if (session('status') === 'profile-updated')
          <span class="status-feedback-msg">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            Cambios guardados
          </span>
        @endif
      </div>
      <button type="submit" class="btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        <span>Guardar Información</span>
      </button>
    </div>
  </form>
</div>
