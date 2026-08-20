<!-- ======================================================================
     SIDEBAR / MENÚ LATERAL DEL MAYORDOMO (ESTILO ADMIN AGROFINCA)
     ====================================================================== -->
<aside class="admin-sidebar" id="adminSidebar">
  <!-- Cabecera del Sidebar con Logo AgroFinca -->
  <div class="sidebar-header">
    <a href="{{ route('mayordomo.dashboard') }}" class="logo-admin">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M11 20 16 4"/>
        <path d="M7 20 12 4"/>
        <path d="M3 20 8 4"/>
        <path d="M22 20H2"/>
        <path d="M14 16h6"/>
      </svg>
      Agro<span>Finca</span>
    </a>
  </div>

  <!-- Navegación del Menú Lateral -->
  <nav class="sidebar-navigation">
    <div class="nav-section">
      <span class="nav-section-title">Módulos Operativos</span>
      <ul>

        <!-- 1. Dashboard -->
        <li class="nav-list-item">
          <a href="{{ route('mayordomo.dashboard') }}" class="nav-item-link {{ request()->routeIs('mayordomo.dashboard') || request()->is('mayordomo/dashboard*') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <span style="font-size: 17px; line-height: 1;">⊞</span>
              <span>Dashboard</span>
            </div>
          </a>
        </li>

        <!-- 2. Solicitudes -->
        <li class="nav-list-item">
          <a href="{{ route('mayordomo.solicitudes.index') }}" class="nav-item-link {{ request()->routeIs('mayordomo.solicitudes*') || request()->is('mayordomo/solicitudes*') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <span style="font-size: 17px; line-height: 1;">📋</span>
              <span>Solicitudes</span>
            </div>
          </a>
        </li>

        <!-- 3. Trabajadores -->
        <li class="nav-list-item">
          <a href="#" class="nav-item-link {{ request()->is('mayordomo/trabajadores*') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <span style="font-size: 17px; line-height: 1;">👷</span>
              <span>Trabajadores</span>
            </div>
          </a>
        </li>

        <!-- 4. Tareas -->
        <li class="nav-list-item">
          <a href="#" class="nav-item-link {{ request()->is('mayordomo/tareas*') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <span style="font-size: 17px; line-height: 1;">✅</span>
              <span>Tareas</span>
            </div>
          </a>
        </li>

        <!-- 5. Préstamos -->
        <li class="nav-list-item">
          <a href="#" class="nav-item-link {{ request()->is('mayordomo/prestamos*') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <span style="font-size: 17px; line-height: 1;">🔑</span>
              <span>Préstamos</span>
            </div>
          </a>
        </li>

        <!-- 6. Inventario -->
        <li class="nav-list-item">
          <a href="#" class="nav-item-link {{ request()->is('mayordomo/inventario*') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <span style="font-size: 17px; line-height: 1;">📦</span>
              <span>Inventario</span>
            </div>
          </a>
        </li>

        <!-- 7. Cultivos -->
        <li class="nav-list-item">
          <a href="#" class="nav-item-link {{ request()->is('mayordomo/cultivos*') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <span style="font-size: 17px; line-height: 1;">🌿</span>
              <span>Cultivos</span>
            </div>
          </a>
        </li>

        <!-- 8. Lotes -->
        <li class="nav-list-item">
          <a href="#" class="nav-item-link {{ request()->is('mayordomo/lotes*') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <span style="font-size: 17px; line-height: 1;">🌎</span>
              <span>Lotes</span>
            </div>
          </a>
        </li>

        <!-- 9. Producción -->
        <li class="nav-list-item">
          <a href="#" class="nav-item-link {{ request()->is('mayordomo/produccion*') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <span style="font-size: 17px; line-height: 1;">📈</span>
              <span>Producción</span>
            </div>
          </a>
        </li>

        <!-- 10. Reportes -->
        <li class="nav-list-item">
          <a href="#" class="nav-item-link {{ request()->is('mayordomo/reportes*') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <span style="font-size: 17px; line-height: 1;">📊</span>
              <span>Reportes</span>
            </div>
          </a>
        </li>

        <!-- Módulo Condicional: Liquidaciones Temporales (si tiene autorización activa) -->
        @php
          $tieneAutorizacionActiva = \App\Models\AutorizacionDelegada::where('id_mayordomo', Auth::id())
              ->where('estado', 'ACTIVA')
              ->where('fecha_fin', '>=', now()->toDateString())
              ->exists();
        @endphp
        @if ($tieneAutorizacionActiva)
          <li class="nav-list-item" style="margin-top: 10px; border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 10px;">
            <a href="#" class="nav-item-link" style="background: rgba(245, 158, 11, 0.12); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3);">
              <div class="nav-item-link-content">
                <span style="font-size: 17px; line-height: 1;">🔐</span>
                <span>Liq. Temporales</span>
              </div>
            </a>
          </li>
        @endif

      </ul>
    </div>
  </nav>
</aside>
