<!-- ======================================================================
     TOPBAR / HEADER SUPERIOR TRABAJADOR
     ====================================================================== -->
<header class="admin-top-header">
  <div class="top-header-left">
    <button class="btn-toggle-menu" id="sidebarToggle" title="Alternar menú lateral">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
    </button>
    <div style="font-size: 14px; font-weight: 600; color: #047857; background: #ecfdf5; padding: 4px 12px; border-radius: 20px;">
      🌾 Portal del Colaborador
    </div>
  </div>

  <div class="top-header-right">
    <!-- Menú de Perfil de Usuario -->
    <div class="user-profile-menu-container">
      <button class="user-profile-btn" data-af-toggle="dropdown" data-af-target="trabajadorProfileMenu">
        <div class="user-profile-avatar" style="background: linear-gradient(135deg, #059669 0%, #065f46 100%);">
          {{ Auth::user()->initials ?? 'TR' }}
        </div>
        <div class="user-profile-text">
          <div class="user-profile-name">{{ Auth::user()->name ?? 'Trabajador' }}</div>
          <div class="user-profile-role" style="color: #059669; font-weight: 700;">TRABAJADOR</div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>

      <!-- Dropdown Menú -->
      <div class="af-dropdown-box" id="trabajadorProfileMenu">
        <div class="dropdown-user-header">
          <div class="dropdown-user-title">{{ Auth::user()->name ?? 'Trabajador' }}</div>
          <div class="dropdown-user-sub">{{ '@' . (Auth::user()->username ?? 'trabajador') }}</div>
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
