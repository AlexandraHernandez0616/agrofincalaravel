<div class="profile-section-card">
  <div class="profile-card-header">
    <div class="profile-card-title-box">
      <div class="profile-card-icon amber">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </div>
      <div>
        <h2 class="profile-card-title">Seguridad y Contraseña</h2>
        <p class="profile-card-desc">Asegúrate de que tu cuenta utilice una contraseña segura para proteger la información operativa.</p>
      </div>
    </div>
  </div>

  @if (session('status') === 'password-updated')
    <div class="af-alert success">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      <span>Tu contraseña ha sido actualizada con éxito.</span>
    </div>
  @endif

  <form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="form-grid-2">
      <!-- Contraseña Actual -->
      <div class="form-group-af full-width">
        <label for="current_password" class="form-label-af">Contraseña Actual <span style="color: #ef4444;">*</span></label>
        <div class="af-input-wrapper">
          <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <input 
            type="password" 
            id="current_password" 
            name="current_password" 
            class="af-form-input @if($errors->updatePassword->has('current_password')) is-invalid @endif" 
            placeholder="Ingresa tu contraseña actual" 
            required 
            autocomplete="current-password"
          />
        </div>
        @if ($errors->updatePassword->has('current_password'))
          <div class="form-field-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ $errors->updatePassword->first('current_password') }}
          </div>
        @endif
      </div>

      <!-- Nueva Contraseña -->
      <div class="form-group-af">
        <label for="password" class="form-label-af">Nueva Contraseña <span style="color: #ef4444;">*</span></label>
        <div class="af-input-wrapper">
          <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-1.5 1.5L10 13l-4 4-2-2 4-4 7.5-7.5m1.5-1.5L21 2z"/><circle cx="7.5" cy="16.5" r="1.5"/></svg>
          <input 
            type="password" 
            id="password" 
            name="password" 
            class="af-form-input @if($errors->updatePassword->has('password')) is-invalid @endif" 
            placeholder="Mínimo 8 caracteres" 
            required 
            autocomplete="new-password"
          />
        </div>
        @if ($errors->updatePassword->has('password'))
          <div class="form-field-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ $errors->updatePassword->first('password') }}
          </div>
        @endif
      </div>

      <!-- Confirmar Nueva Contraseña -->
      <div class="form-group-af">
        <label for="password_confirmation" class="form-label-af">Confirmar Nueva Contraseña <span style="color: #ef4444;">*</span></label>
        <div class="af-input-wrapper">
          <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          <input 
            type="password" 
            id="password_confirmation" 
            name="password_confirmation" 
            class="af-form-input @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif" 
            placeholder="Repite la nueva contraseña" 
            required 
            autocomplete="new-password"
          />
        </div>
        @if ($errors->updatePassword->has('password_confirmation'))
          <div class="form-field-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ $errors->updatePassword->first('password_confirmation') }}
          </div>
        @endif
      </div>

      <!-- Requisitos de Seguridad -->
      <div class="password-requirements-box">
        <div class="password-requirements-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
          Recomendaciones para una contraseña segura:
        </div>
        <ul class="password-requirements-list">
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Mínimo 8 caracteres
          </li>
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Combina letras y números
          </li>
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            No utilices datos personales obvios
          </li>
        </ul>
      </div>
    </div>

    <!-- Footer / Botón Guardar -->
    <div class="profile-form-footer">
      <div>
        @if (session('status') === 'password-updated')
          <span class="status-feedback-msg">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            Contraseña actualizada
          </span>
        @endif
      </div>
      <button type="submit" class="btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <span>Actualizar Contraseña</span>
      </button>
    </div>
  </form>
</div>
