<!-- ======================================================================
     TOPBAR / HEADER SUPERIOR MAYORDOMO (ESTILO ADMIN AGROFINCA)
     ====================================================================== -->
<header class="admin-top-header">
  <div class="top-header-left">
    <button class="btn-toggle-menu" id="sidebarToggle" title="Alternar menú lateral">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
    </button>

    <!-- Título del Sistema o Barra de Búsqueda -->
    <div class="top-search-wrapper">
      <svg class="search-svg-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
      <input type="text" class="top-search-input" id="mayordomoGlobalSearch" placeholder="Buscar trabajadores, tareas, insumos..." />
      <span class="search-key-hint">Ctrl+K</span>
    </div>
  </div>

  <div class="top-header-right">
    <!-- Badge Mayordomo -->
    <span style="background: rgba(16, 185, 129, 0.15); color: #059669; font-weight: 700; font-size: 12px; padding: 4px 12px; border-radius: 20px; border: 1px solid rgba(16, 185, 129, 0.3);">
      Mayordomo
    </span>

    <!-- Notificaciones Operativas -->
    <div style="position: relative;">
      <button class="header-icon-button" data-af-toggle="dropdown" data-af-target="mayordomoNotificationsMenu" title="Notificaciones">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
        <span class="header-badge-dot"></span>
      </button>

      <!-- Menú Desplegable Notificaciones -->
      <div class="af-dropdown-box" id="mayordomoNotificationsMenu" style="width: 320px;">
        <div class="dropdown-user-header">
          <div class="dropdown-user-title">Notificaciones Operativas</div>
          <div class="dropdown-user-sub">Alertas y tareas recientes en campo</div>
        </div>
        <div style="display: flex; gap: 10px; padding: 10px 12px; border-radius: 12px; background: var(--bg-color); margin-bottom: 8px;">
          <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(16, 185, 129, 0.2); color: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          </div>
          <div>
            <div style="font-size: 13px; font-weight: 500;">Registro de asistencia activo para la jornada de hoy.</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Sistema Operativo</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Menú de Perfil de Usuario -->
    <div class="user-profile-menu-container">
      <button class="user-profile-btn" data-af-toggle="dropdown" data-af-target="mayordomoProfileMenu">
        <div class="user-profile-avatar" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
          {{ Auth::user()->initials ?? 'MY' }}
        </div>
        <div class="user-profile-text">
          <div class="user-profile-name">{{ Auth::user()->name ?? 'Mayordomo' }}</div>
          <div class="user-profile-role">{{ Auth::user()->rol ?? 'MAYORDOMO' }}</div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>

      <!-- Dropdown Menú -->
      <div class="af-dropdown-box" id="mayordomoProfileMenu">
        <div class="dropdown-user-header">
          <div class="dropdown-user-title">{{ Auth::user()->name ?? 'Mayordomo' }}</div>
          <div class="dropdown-user-sub">{{ '@' . (Auth::user()->username ?? 'mayordomo') }}</div>
        </div>
        <a href="{{ route('profile.edit') }}" class="dropdown-link-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>Mi Perfil</span>
        </a>
        <div style="height: 1px; background: var(--border); margin: 6px 0;"></div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="dropdown-link-item item-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
            <span>Cerrar Sesión</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</header>
