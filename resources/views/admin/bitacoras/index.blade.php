<x-admin-layout title="Bitácora de Operaciones">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Bitácora de Operaciones</h1>
    <p>Auditoría y trazabilidad de acciones críticas del sistema</p>
  </x-slot>

  <!-- Notificaciones Flash -->
  @if (session('success'))
    <div class="af-alert success">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if (session('error'))
    <div class="af-alert error">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  <!-- ======================================================================
       TARJETAS KPI SUPERIORES (TOTAL, HOY, USUARIOS, MÓDULOS)
       ====================================================================== -->
  <div class="bitacora-kpi-grid">
    <!-- 1. Total Registros -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Total Registros</div>
      <div class="lotes-kpi-number">{{ $totalRegistros }}</div>
    </div>

    <!-- 2. Operaciones Hoy -->
    <div class="lotes-kpi-card blue">
      <div class="lotes-kpi-label">Operaciones Hoy</div>
      <div class="lotes-kpi-number">{{ $operacionesHoy }}</div>
    </div>

    <!-- 3. Usuarios con Actividad -->
    <div class="lotes-kpi-card yellow">
      <div class="lotes-kpi-label">Usuarios con Actividad</div>
      <div class="lotes-kpi-number">{{ $usuariosActivos }}</div>
    </div>

    <!-- 4. Módulos Registrados -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Módulos Registrados</div>
      <div class="lotes-kpi-number">{{ $modulosRegistrados }}</div>
    </div>
  </div>

  <!-- ======================================================================
       BARRA DE BÚSQUEDA Y FILTROS EN TIEMPO REAL
       ====================================================================== -->
  <div class="table-toolbar-row">
    <!-- Buscador en tiempo real -->
    <div class="table-search-bar-wrap" style="flex: 1;">
      <span class="search-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
      </span>
      <input 
        type="text" 
        id="bitLiveSearch" 
        class="table-search-bar-input" 
        placeholder="Buscar en bitácora..." 
        value="{{ $search ?? '' }}" 
        autocomplete="off"
      />
    </div>

    <!-- Filtro de Módulo -->
    <div class="table-filter-select-wrap">
      <select id="bitModuleFilter" class="table-filter-select">
        <option value="todos" {{ empty($modulo) || strtolower($modulo) === 'todos' ? 'selected' : '' }}>Todos los módulos</option>
        @foreach ($modulosDisponibles as $m)
          <option value="{{ strtolower($m) }}" {{ strtolower($modulo ?? '') === strtolower($m) ? 'selected' : '' }}>
            {{ $m }}
          </option>
        @endforeach
      </select>
    </div>

    <!-- Filtro de Acción -->
    <div class="table-filter-select-wrap">
      <select id="bitActionFilter" class="table-filter-select">
        <option value="todos" {{ empty($accion) || strtolower($accion) === 'todos' ? 'selected' : '' }}>Todas las acciones</option>
        @foreach ($accionesDisponibles as $a)
          <option value="{{ strtolower($a) }}" {{ strtolower($accion ?? '') === strtolower($a) ? 'selected' : '' }}>
            {{ $a }}
          </option>
        @endforeach
      </select>
    </div>
  </div>

  <!-- ======================================================================
       TABLA PRINCIPAL DE BITÁCORA
       ====================================================================== -->
  <div class="af-table-card">
    <div class="af-table-responsive">
      <table class="af-table-data" id="bitacorasTable">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Módulo</th>
            <th>Acción</th>
            <th>Descripción</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($bitacoras as $bit)
            <tr 
              data-module="{{ strtolower($bit->modulo ?? '') }}"
              data-action="{{ strtolower($bit->operacion ?? '') }}"
              data-user="{{ strtolower($bit->usuario_nombre) }}"
            >
              <td style="font-weight: 500; color: #0f172a;">{{ $bit->formatted_fecha }}</td>
              <td style="color: var(--text-muted); font-size: 13px;">{{ $bit->formatted_hora }}</td>
              <td style="font-weight: 600; color: var(--secondary-color);">{{ $bit->usuario_nombre }}</td>
              <td>
                @if (strtoupper($bit->usuario_rol) === 'ADMINISTRADOR' || strtoupper($bit->usuario_rol) === 'ADMIN')
                  <span class="pill-status pill-status-blue">ADMIN</span>
                @elseif (strtoupper($bit->usuario_rol) === 'MAYORDOMO')
                  <span class="pill-status pill-status-amber">MAYORDOMO</span>
                @else
                  <span class="pill-status pill-status-active">{{ $bit->usuario_rol }}</span>
                @endif
              </td>
              <td>
                <span style="font-weight: 600; color: #334155;">{{ $bit->modulo }}</span>
              </td>
              <td>
                @if (stripos($bit->operacion, 'crea') !== false || stripos($bit->operacion, 'regist') !== false || stripos($bit->operacion, 'liquid') !== false)
                  <span class="pill-status pill-status-active">{{ $bit->operacion }}</span>
                @elseif (stripos($bit->operacion, 'actualiz') !== false || stripos($bit->operacion, 'edit') !== false || stripos($bit->operacion, 'modific') !== false)
                  <span class="pill-status pill-status-blue">{{ $bit->operacion }}</span>
                @elseif (stripos($bit->operacion, 'elimin') !== false || stripos($bit->operacion, 'borr') !== false || stripos($bit->operacion, 'revoc') !== false)
                  <span class="pill-status pill-status-red">{{ $bit->operacion }}</span>
                @else
                  <span class="pill-status pill-status-gray">{{ $bit->operacion }}</span>
                @endif
              </td>
              <td style="color: #475569; font-size: 13px; max-width: 320px;">
                {{ $bit->detalle ?? '—' }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="table-empty-state" style="padding: 40px 20px; text-align: center;">
                <p style="color: var(--text-muted); font-size: 14.5px; margin: 0;">No hay registros en la bitácora</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pie de Tabla con Contador de Registros -->
    <div style="display: flex; justify-content: flex-end; align-items: center; padding: 14px 24px; border-top: 1px solid var(--border); background: #fafafa;">
      <span id="bitacoraCounterText" style="font-size: 12.5px; color: var(--text-muted);">
        Mostrando {{ $bitacoras->count() }} registros
      </span>
    </div>
  </div>

  <!-- ======================================================================
       SCRIPTS: FILTRADO EN TIEMPO REAL
       ====================================================================== -->
  @push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const liveSearch = document.getElementById('bitLiveSearch');
      const moduleFilter = document.getElementById('bitModuleFilter');
      const actionFilter = document.getElementById('bitActionFilter');
      const tableRows = document.querySelectorAll('#bitacorasTable tbody tr');
      const counterText = document.getElementById('bitacoraCounterText');

      function filterBitacora() {
        const query = (liveSearch ? liveSearch.value : '').toLowerCase().trim();
        const selectedModule = (moduleFilter ? moduleFilter.value : 'todos').toLowerCase();
        const selectedAction = (actionFilter ? actionFilter.value : 'todos').toLowerCase();

        let visibleCount = 0;

        tableRows.forEach(row => {
          if (row.querySelector('.table-empty-state')) return;

          const rowText = row.textContent.toLowerCase();
          const rowMod = (row.getAttribute('data-module') || '').toLowerCase();
          const rowAct = (row.getAttribute('data-action') || '').toLowerCase();
          const rowUser = (row.getAttribute('data-user') || '').toLowerCase();

          const matchesQuery = !query || rowText.includes(query) || rowUser.includes(query);
          const matchesModule = (selectedModule === 'todos') || rowMod.includes(selectedModule);
          const matchesAction = (selectedAction === 'todos') || rowAct.includes(selectedAction);

          if (matchesQuery && matchesModule && matchesAction) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });

        if (counterText && tableRows.length > 0 && !tableRows[0].querySelector('.table-empty-state')) {
          counterText.textContent = `Mostrando ${visibleCount} registros`;
        }
      }

      if (liveSearch) liveSearch.addEventListener('input', filterBitacora);
      if (moduleFilter) moduleFilter.addEventListener('change', filterBitacora);
      if (actionFilter) actionFilter.addEventListener('change', filterBitacora);
    });
  </script>
  @endpush

</x-admin-layout>
