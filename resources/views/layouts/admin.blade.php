<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? 'Panel de Administración' }} - AgroFinca</title>
  <meta name="description" content="Panel Administrativo de AgroFinca - Sistema Inteligente de Gestión Agrícola." />

  <!-- Google Fonts Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

  <!-- Estilos AgroFinca Admin -->
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=3.0" />
  @stack('styles')
</head>
<body>

  <!-- Overlay para móviles al desplegar menú -->
  <div class="admin-backdrop-overlay" id="sidebarOverlay"></div>

  <div class="admin-layout">

    <!-- ======================================================================
         SIDEBAR / MENÚ LATERAL (ESTILO AGROFINCA #0f172a & #10b981)
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
        <!-- Item Intacto: Dashboard -->
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
          $isOperacionesActive = request()->is('mayordomos*') || request()->is('trabajadores*') || request()->is('asistencias*'); 
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
                <a href="{{ url('/asistencias') }}" class="nav-subitem-link {{ request()->is('asistencias*') ? 'active' : '' }}">
                  <div class="nav-subitem-content">
                    <span class="subitem-dot"></span>
                    <span>Control de Asistencia</span>
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

      <!-- Pie del Menú Lateral -->
      
    </aside>

    <!-- ======================================================================
         ÁREA DE CONTENIDO PRINCIPAL
         ====================================================================== -->
    <div class="admin-content-area">
      <!-- Header Superior Pegajoso (Blur Glassmorphism) -->
      <header class="admin-top-header">
        <div class="top-header-left">
          <button class="btn-toggle-menu" id="sidebarToggle" title="Alternar menú lateral">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
          </button>

          <!-- Barra de Búsqueda -->
          <div class="top-search-wrapper">
            <svg class="search-svg-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" class="top-search-input" id="adminGlobalSearch" placeholder="Buscar cultivos, tareas, insumos..." />
            <span class="search-key-hint">Ctrl+K</span>
          </div>
        </div>

        <div class="top-header-right">
          <!-- Notificaciones -->
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

          <!-- Perfil de Usuario -->
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

      <!-- Contenedor de Página -->
      <main class="admin-page-container">
        @isset($header)
          <div class="admin-page-header">
            <div>
              {{ $header }}
            </div>
            @isset($actions)
              <div class="admin-page-actions">
                {{ $actions }}
              </div>
            @endisset
          </div>
        @endisset

        {{ $slot ?? '' }}
        @yield('content')
      </main>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('js/admin.js') }}?v=3.0"></script>
  @stack('scripts')
</body>
</html>
