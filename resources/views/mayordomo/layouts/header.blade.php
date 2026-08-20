<!-- ======================================================================
     TOPBAR / HEADER SUPERIOR MAYORDOMO (SIN SIDEBAR)
     ====================================================================== -->
<header class="admin-top-header" style="padding: 12px 28px; background: #ffffff; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 100; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
  <div class="top-header-left" style="display: flex; align-items: center; gap: 16px;">
    <!-- Logo AgroFinca -->
    <a href="{{ route('mayordomo.dashboard') }}" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
      <div style="background: #10b981; color: #fff; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(16,185,129,0.3);">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
      </div>
      <div>
        <span style="font-size: 18px; font-weight: 800; color: #0f172a; font-family: 'Outfit', sans-serif;">Agro<span style="color: #10b981;">Finca</span></span>
        <span style="font-size: 11px; font-weight: 700; color: #059669; margin-left: 6px; background: #ecfdf5; padding: 2px 8px; border-radius: 6px; letter-spacing: 0.5px; text-transform: uppercase;">Mayordomo</span>
      </div>
    </a>
  </div>

  <div class="top-header-right">
    <!-- Menú de Perfil de Usuario -->
    <div class="user-profile-menu-container">
      <button class="user-profile-btn" data-af-toggle="dropdown" data-af-target="mayordomoProfileMenu">
        <div class="user-profile-avatar" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
          {{ Auth::user()->initials ?? 'MY' }}
        </div>
        <div class="user-profile-text">
          <div class="user-profile-name">{{ Auth::user()->name ?? 'Mayordomo' }}</div>
          <div class="user-profile-role" style="color: #059669; font-weight: 700;">MAYORDOMO</div>
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
