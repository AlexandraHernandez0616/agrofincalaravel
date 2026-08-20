<x-admin-layout title="Mi Perfil">
  <x-slot name="header">
    <h1>Mi Perfil de Usuario</h1>
    <p>Gestiona tu información personal, credenciales de acceso y preferencias en el sistema <strong>AgroFinca</strong>.</p>
  </x-slot>

  <x-slot name="actions">
    <a href="{{ route('dashboard') }}" class="btn-outline">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" x2="5" y1="12" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      <span>Volver al Dashboard</span>
    </a>
  </x-slot>

  <!-- Layout Principal de Perfil -->
  <div class="profile-grid">
    
    <!-- Columna Izquierda: Tarjeta Hero del Usuario -->
    <aside class="profile-hero-card">
      <div class="profile-hero-banner">
        <div class="profile-hero-pattern"></div>
        <span class="profile-role-badge-banner">{{ $user->rol ?? 'ADMINISTRADOR' }}</span>
      </div>

      <div class="profile-hero-body">
        <div class="profile-avatar-wrapper">
          {{ $user->initials }}
          <span class="profile-online-indicator" title="Usuario activo"></span>
        </div>

        <h2 class="profile-hero-name">{{ $user->name }}</h2>
        <div class="profile-hero-handle">{{ '@' . $user->username }}</div>

        <div class="profile-hero-divider"></div>

        <!-- Lista de Detalles -->
        <div class="profile-info-list">
          <!-- Documento -->
          <div class="profile-info-item">
            <div class="profile-info-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            </div>
            <div class="profile-info-content">
              <div class="profile-info-label">Documento</div>
              <div class="profile-info-val">{{ $user->documento ?? 'No registrado' }}</div>
            </div>
          </div>

          <!-- Teléfono -->
          <div class="profile-info-item">
            <div class="profile-info-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </div>
            <div class="profile-info-content">
              <div class="profile-info-label">Teléfono</div>
              <div class="profile-info-val">{{ $user->telefono ?? 'Sin teléfono registrado' }}</div>
            </div>
          </div>

          <!-- Estado de Cuenta -->
          <div class="profile-info-item">
            <div class="profile-info-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div class="profile-info-content">
              <div class="profile-info-label">Estado de Cuenta</div>
              <div class="profile-info-val">
                @if ($user->activo ?? true)
                  <span class="badge" style="font-size: 11px; padding: 2px 8px;">Activo</span>
                @else
                  <span class="badge badge-red" style="font-size: 11px; padding: 2px 8px;">Inactivo</span>
                @endif
              </div>
            </div>
          </div>

          <!-- Fecha de Creación -->
          <div class="profile-info-item">
            <div class="profile-info-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
            </div>
            <div class="profile-info-content">
              <div class="profile-info-label">Miembro Desde</div>
              <div class="profile-info-val">{{ $user->formatted_fecha_creacion }}</div>
            </div>
          </div>
        </div>

        <div class="profile-hero-divider"></div>

        <!-- Acceso Rápido Sitio Web -->
        <a href="{{ url('/') }}" target="_blank" class="btn-outline" style="width: 100%; font-size: 13.5px; padding: 9px 16px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
          <span>Ver Sitio Web AgroFinca</span>
        </a>
      </div>
    </aside>

    <!-- Columna Derecha: Secciones y Formularios -->
    <main class="profile-main-col">
      <!-- Formulario 1: Datos Personales -->
      @include('admin.profile.partials.update-profile-information-form')

      <!-- Formulario 2: Contraseña y Seguridad -->
      @include('admin.profile.partials.update-password-form')
    </main>

  </div>
</x-admin-layout>
