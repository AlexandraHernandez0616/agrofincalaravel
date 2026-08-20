<!-- ======================================================================
     TOPBAR / HEADER SUPERIOR AGROFINCA
     ====================================================================== -->
<header class="admin-top-header">
  <div class="top-header-left">
    <button class="btn-toggle-menu" id="sidebarToggle" title="Alternar menú lateral">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
    </button>

    <!-- Barra de Búsqueda Global -->
    <div class="top-search-wrapper">
      <svg class="search-svg-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
      <input type="text" class="top-search-input" id="adminGlobalSearch" placeholder="Buscar cultivos, tareas, insumos..." />
      <span class="search-key-hint">Ctrl+K</span>
    </div>
  </div>

  <div class="top-header-right">
    <!-- Notificaciones Operativas -->
    <div style="position: relative;">
      <button class="header-icon-button" data-af-toggle="dropdown" data-af-target="notificationsMenu" title="Notificaciones">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
        <span class="header-badge-dot"></span>
      </button>

      <!-- Menú Desplegable Notificaciones -->
      <div class="af-dropdown-box" id="notificationsMenu" style="width: 320px;">
        <div class="dropdown-user-header">
          <div class="dropdown-user-title">Notificaciones Operativas</div>
          <div class="dropdown-user-sub">Alertas recientes en la finca</div>
        </div>
        <div style="display: flex; gap: 10px; padding: 10px 12px; border-radius: 12px; background: var(--bg-color); margin-bottom: 8px;">
          <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(16, 185, 129, 0.2); color: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          </div>
          <div>
            <div style="font-size: 13px; font-weight: 500;">Tarea <strong>Fertilización Lote A</strong> finalizada.</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Hace 20 min</div>
          </div>
        </div>
        <div style="display: flex; gap: 10px; padding: 10px 12px; border-radius: 12px; background: #fffbeb;">
          <div style="width: 32px; height: 32px; border-radius: 50%; background: #fde68a; color: #b45309; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
          </div>
          <div>
            <div style="font-size: 13px; font-weight: 500; color: #92400e;">Insumo <strong>Urea 50kg</strong> con stock bajo (3 bultos).</div>
            <div style="font-size: 11px; color: #b45309; margin-top: 2px;">Hace 1 hora</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Menú de Perfil de Usuario -->
    <div class="user-profile-menu-container">
      <button class="user-profile-btn" data-af-toggle="dropdown" data-af-target="profileMenu">
        <div class="user-profile-avatar">
          {{ Auth::user()->initials ?? 'AD' }}
        </div>
        <div class="user-profile-text">
          <div class="user-profile-name">{{ Auth::user()->name ?? 'Administrador' }}</div>
          <div class="user-profile-role">{{ Auth::user()->rol ?? 'ADMIN' }}</div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>

      <!-- Dropdown Menú -->
      <div class="af-dropdown-box" id="profileMenu">
        <div class="dropdown-user-header">
          <div class="dropdown-user-title">{{ Auth::user()->name ?? 'Administrador' }}</div>
          <div class="dropdown-user-sub">{{ '@' . (Auth::user()->username ?? 'admin') }}</div>
        </div>
        <a href="{{ route('profile.edit') }}" class="dropdown-link-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>Mi Perfil</span>
        </a>
        <a href="{{ url('/') }}" target="_blank" class="dropdown-link-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
          <span>Ver Sitio Web</span>
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
