<!-- ======================================================================
     SIDEBAR / MENÚ LATERAL AGROFINCA (#0f172a & #10b981)
     ====================================================================== -->
<aside class="admin-sidebar" id="adminSidebar">
  <!-- Marca / Logo AgroFinca -->
  <div class="sidebar-header">
    <a href="{{ route('dashboard') }}" class="logo-admin">
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
    <!-- Item: Dashboard -->
    <div class="nav-section" style="margin-bottom: 12px;">
      <ul>
        <li class="nav-list-item">
          <a href="{{ route('dashboard') }}" class="nav-item-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="nav-item-link-content">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
              <span>Dashboard</span>
            </div>
          </a>
        </li>
      </ul>
    </div>

    <!-- 1. Botón Desplegable: Personal y Operaciones -->
    @php 
      $isOperacionesActive = request()->is('mayordomos*') || request()->is('trabajadores*') || request()->is('solicitudes*') || request()->is('asistencias*'); 
    @endphp
    <div class="nav-accordion-group {{ $isOperacionesActive ? 'open' : '' }}">
      <button type="button" class="nav-accordion-btn {{ $isOperacionesActive ? 'active-group' : '' }}" data-af-toggle="sidebar-accordion">
        <div class="nav-accordion-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span>Personal y Operaciones</span>
        </div>
        <svg class="accordion-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>
      <div class="nav-accordion-collapse">
        <ul class="nav-sublist">
          <li>
            <a href="{{ url('/mayordomos') }}" class="nav-subitem-link {{ request()->is('mayordomos*') ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Mayordomos</span>
              </div>
            </a>
          </li>
          <li>
            <a href="{{ url('/trabajadores') }}" class="nav-subitem-link {{ request()->is('trabajadores*') ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Trabajadores</span>
              </div>
            </a>
          </li>
          <li>
            <a href="{{ url('/solicitudes-registro') }}" class="nav-subitem-link {{ request()->is('solicitudes*') ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Solicitudes de Registro</span>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- 2. Botón Desplegable: Gestión Agrícola -->
    @php 
      $isAgricolaActive = request()->is('inventario*') || request()->is('bodega*') || request()->is('insumos*') || request()->is('cultivos*') || request()->is('lotes*') || request()->is('producciones*'); 
    @endphp
    <div class="nav-accordion-group {{ $isAgricolaActive ? 'open' : '' }}">
      <button type="button" class="nav-accordion-btn {{ $isAgricolaActive ? 'active-group' : '' }}" data-af-toggle="sidebar-accordion">
        <div class="nav-accordion-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a9 9 0 0 1 9 9c0 4.97-4.03 9-9 9A9 9 0 0 1 3 11a9 9 0 0 1 9-9Z"/><path d="M12 7v10"/><path d="M8 12h8"/></svg>
          <span>Gestión Agrícola</span>
        </div>
        <svg class="accordion-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>
      <div class="nav-accordion-collapse">
        <ul class="nav-sublist">
          <li>
            <a href="{{ url('/inventario') }}" class="nav-subitem-link {{ (request()->is('inventario*') || request()->is('bodega*') || request()->is('insumos*')) ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Gestión de Inventario</span>
              </div>
            </a>
          </li>
          <li>
            <a href="{{ url('/cultivos') }}" class="nav-subitem-link {{ (request()->is('cultivos*') || request()->is('lotes*') || request()->is('producciones*')) ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Lotes, Cultivos y Producción</span>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- 3. Botón Desplegable: Finanzas y Pagos -->
    @php 
      $isFinanzasActive = request()->is('tarifas*') || request()->is('liquidaciones*') || request()->is('pagos*'); 
    @endphp
    <div class="nav-accordion-group {{ $isFinanzasActive ? 'open' : '' }}">
      <button type="button" class="nav-accordion-btn {{ $isFinanzasActive ? 'active-group' : '' }}" data-af-toggle="sidebar-accordion">
        <div class="nav-accordion-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          <span>Finanzas y Pagos</span>
        </div>
        <svg class="accordion-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>
      <div class="nav-accordion-collapse">
        <ul class="nav-sublist">
          <li>
            <a href="{{ url('/tarifas') }}" class="nav-subitem-link {{ request()->is('tarifas*') ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Tarifas</span>
              </div>
            </a>
          </li>
          <li>
            <a href="{{ url('/liquidaciones') }}" class="nav-subitem-link {{ request()->is('liquidaciones*') ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Liquidaciones</span>
              </div>
            </a>
          </li>
          <li>
            <a href="{{ url('/pagos') }}" class="nav-subitem-link {{ request()->is('pagos*') ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Pagos</span>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- 4. Botón Desplegable: Auditoría y Sistema -->
    @php 
      $isAuditoriaActive = request()->is('autorizaciones*') || request()->is('bitacoras*') || request()->is('reportes*'); 
    @endphp
    <div class="nav-accordion-group {{ $isAuditoriaActive ? 'open' : '' }}">
      <button type="button" class="nav-accordion-btn {{ $isAuditoriaActive ? 'active-group' : '' }}" data-af-toggle="sidebar-accordion">
        <div class="nav-accordion-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
          <span>Auditoría y Sistema</span>
        </div>
        <svg class="accordion-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>
      <div class="nav-accordion-collapse">
        <ul class="nav-sublist">
          <li>
            <a href="{{ url('/autorizaciones') }}" class="nav-subitem-link {{ request()->is('autorizaciones*') ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Autorizaciones Delegadas</span>
              </div>
            </a>
          </li>
          <li>
            <a href="{{ url('/bitacoras') }}" class="nav-subitem-link {{ request()->is('bitacoras*') ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Bitácoras de Operación</span>
              </div>
            </a>
          </li>
          <li>
            <a href="{{ url('/reportes') }}" class="nav-subitem-link {{ request()->is('reportes*') ? 'active' : '' }}">
              <div class="nav-subitem-content">
                <span class="subitem-dot"></span>
                <span>Reportes</span>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</aside>
