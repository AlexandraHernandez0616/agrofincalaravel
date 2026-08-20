<!-- ======================================================================
     SIDEBAR / MENÚ LATERAL DEL TRABAJADOR
     ====================================================================== -->
<aside class="admin-sidebar" id="adminSidebar">
  <!-- Cabecera con Logo AgroFinca -->
  <div class="sidebar-header">
    <a href="{{ route('trabajador.dashboard') }}" class="sidebar-logo-wrap">
      <div class="sidebar-logo-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
      </div>
      <div class="sidebar-brand-text">
        <span class="brand-title">AgroFinca</span>
        <span class="brand-subtitle" style="color: #059669; font-weight: 700;">COLABORADOR</span>
      </div>
    </a>
  </div>

  <!-- Navegación -->
  <nav class="sidebar-nav">
    <!-- 1. Mi Panel -->
    <a href="{{ route('trabajador.dashboard') }}" class="nav-item-link {{ request()->is('trabajador/dashboard*') ? 'active' : '' }}">
      <div class="nav-item-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
        <span>Mi Portal</span>
      </div>
    </a>

    <!-- 2. Mi Perfil -->
    <a href="{{ route('profile.edit') }}" class="nav-item-link {{ request()->is('profile*') ? 'active' : '' }}">
      <div class="nav-item-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>Mi Cuenta</span>
      </div>
    </a>
  </nav>
</aside>
