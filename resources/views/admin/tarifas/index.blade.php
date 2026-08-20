<x-admin-layout title="Gestión de Tarifas de Pago">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Gestión de Tarifas de Pago</h1>
    <p>Administra las tarifas de pago del sistema</p>
  </x-slot>

  <!-- Botón de Acción Superior -->
  <x-slot name="actions">
    <button type="button" class="btn-primary" data-af-modal-open="createTarifaModal">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
      <span>+ Nueva Tarifa</span>
    </button>
  </x-slot>

  <!-- Notificaciones Flash -->
  @if (session('success'))
    <div class="af-alert success">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if ($errors->any())
    <div class="af-alert error">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
      <div>
        <strong>Por favor corrige los siguientes errores:</strong>
        <ul style="margin-top: 4px; padding-left: 18px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  <!-- ======================================================================
       TARJETAS KPI SUPERIORES (TOTAL TARIFAS, ACTIVAS, INACTIVAS)
       ====================================================================== -->
  <div class="tarifas-kpi-grid">
    <!-- 1. Total Tarifas -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Total Tarifas</div>
      <div class="lotes-kpi-number">{{ $totalTarifas }}</div>
    </div>

    <!-- 2. Tarifas Activas -->
    <div class="lotes-kpi-card blue">
      <div class="lotes-kpi-label">Tarifas Activas</div>
      <div class="lotes-kpi-number">{{ $tarifasActivas }}</div>
    </div>

    <!-- 3. Tarifas Inactivas -->
    <div class="lotes-kpi-card yellow">
      <div class="lotes-kpi-label">Tarifas Inactivas</div>
      <div class="lotes-kpi-number">{{ $tarifasInactivas }}</div>
    </div>
  </div>

  <!-- ======================================================================
       BARRA DE BÚSQUEDA Y FILTROS EN TIEMPO REAL
       ====================================================================== -->
  <div class="table-toolbar-row">
    <div class="table-search-bar-wrap" style="flex: 1;">
      <span class="search-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
      </span>
      <input 
        type="text" 
        id="tarifaLiveSearch" 
        class="table-search-bar-input" 
        placeholder="Buscar por tipo de tarifa..." 
        value="{{ $search ?? '' }}" 
        autocomplete="off"
      />
    </div>

    <!-- Filtro de Estado -->
    <div class="table-filter-select-wrap">
      <select id="tarifaStateFilter" class="table-filter-select">
        <option value="todos" {{ empty($estado) || strtolower($estado) === 'todos' ? 'selected' : '' }}>Todos los estados</option>
        <option value="activa" {{ strtolower($estado ?? '') === 'activa' ? 'selected' : '' }}>Activa</option>
        <option value="inactiva" {{ strtolower($estado ?? '') === 'inactiva' ? 'selected' : '' }}>Inactiva</option>
      </select>
    </div>

    <!-- Filtro de Tipo de Tarifa -->
    <div class="table-filter-select-wrap">
      <select id="tarifaTypeFilter" class="table-filter-select">
        <option value="todos">Todos los tipos</option>
        @foreach ($tiposDisponibles as $tipoOpt)
          <option value="{{ strtolower($tipoOpt) }}" {{ strtolower($tipo ?? '') === strtolower($tipoOpt) ? 'selected' : '' }}>
            {{ $tipoOpt }}
          </option>
        @endforeach
      </select>
    </div>
  </div>

  <!-- ======================================================================
       TABLA PRINCIPAL DE TARIFAS DE PAGO
       ====================================================================== -->
  <div class="af-table-card">
    <div class="af-table-responsive">
      <table class="af-table-data" id="tarifasTable">
        <thead>
          <tr>
            <th>Tipo de Tarifa</th>
            <th>Valor (COP)</th>
            <th>Fecha de Inicio</th>
            <th>Fecha Fin</th>
            <th>Estado</th>
            <th style="text-align: right; padding-right: 24px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($tarifas as $tarifa)
            <tr data-status="{{ $tarifa->is_active ? 'activa' : 'inactiva' }}" data-type="{{ strtolower($tarifa->tipo_pago) }}">
              <td style="font-weight: 500; color: var(--secondary-color);">{{ $tarifa->tipo_pago }}</td>
              <td style="font-weight: 600; color: #0f172a;">{{ $tarifa->formatted_valor }}</td>
              <td>{{ $tarifa->formatted_fecha_inicio }}</td>
              <td>{{ $tarifa->formatted_fecha_fin }}</td>
              <td>
                @if ($tarifa->is_active)
                  <span class="pill-status pill-status-active">Activa</span>
                @else
                  <span class="pill-status pill-status-inactive">Inactiva</span>
                @endif
              </td>
              <td style="text-align: right; padding-right: 24px;">
                <div class="table-actions-cell" style="justify-content: flex-end; align-items: center; gap: 8px;">
                  <!-- Botón Editar (Icono Lápiz Naranja) -->
                  <button 
                    type="button" 
                    class="action-icon-btn btn-edit-orange" 
                    title="Editar tarifa" 
                    data-af-modal-open="editTarifaModal_{{ $tarifa->id_tarifa }}"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                  </button>

                  <!-- Botón Deshabilitar / Habilitar (Pill Rojo/Verde) -->
                  <form action="{{ route('tarifas.toggle-status', $tarifa->id_tarifa) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('PATCH')
                    <button 
                      type="submit" 
                      class="pill-btn-toggle {{ $tarifa->is_active ? 'deshabilitar' : 'habilitar' }}"
                      title="{{ $tarifa->is_active ? 'Deshabilitar tarifa' : 'Habilitar tarifa' }}"
                    >
                      {{ $tarifa->is_active ? 'Deshabilitar' : 'Habilitar' }}
                    </button>
                  </form>

                  <!-- Botón Eliminar (Icono Papelera) -->
                  <form action="{{ route('tarifas.destroy', $tarifa->id_tarifa) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar esta tarifa?');">
                    @csrf
                    @method('DELETE')
                    <button 
                      type="submit" 
                      class="action-icon-btn btn-delete" 
                      title="Eliminar tarifa"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="table-empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <p style="margin-top: 8px; color: var(--text-muted);">No hay tarifas de pago registradas actualmente.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- ======================================================================
       MODAL 1: REGISTRAR NUEVA TARIFA
       ====================================================================== -->
  <div class="af-modal-overlay" id="createTarifaModal">
    <div class="af-modal-card" style="max-width: 540px;">
      <div class="af-modal-header">
        <div class="af-modal-title">
          <div class="modal-title-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <div>
            <h3>Registrar Nueva Tarifa</h3>
            <p>Configura un valor de pago por labor o producción</p>
          </div>
        </div>
        <button type="button" class="btn-close-modal" data-af-modal-close="createTarifaModal">&times;</button>
      </div>

      <form action="{{ route('tarifas.store') }}" method="POST">
        @csrf
        <div class="af-modal-body">
          <div class="af-form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="af-form-group">
              <label for="tipo_pago">Tipo de Tarifa <span class="req">*</span></label>
              <input 
                type="text" 
                id="tipo_pago" 
                name="tipo_pago" 
                class="af-form-input" 
                placeholder="Ej: Producción, Jornada..." 
                list="tipos_sugeridos"
                required 
              />
              <datalist id="tipos_sugeridos">
                <option value="Producción">
                <option value="Jornada">
                <option value="Hora">
                <option value="Kilo">
                <option value="Poda">
                <option value="Fumigación">
                <option value="Siembra">
              </datalist>
            </div>

            <div class="af-form-group">
              <label for="valor">Valor en Pesos (COP) <span class="req">*</span></label>
              <input 
                type="number" 
                step="0.01" 
                id="valor" 
                name="valor" 
                class="af-form-input" 
                placeholder="Ej: 50000" 
                required 
              />
            </div>

            <div class="af-form-group">
              <label for="fecha_inicio_vigencia">Fecha de Inicio <span class="req">*</span></label>
              <input 
                type="date" 
                id="fecha_inicio_vigencia" 
                name="fecha_inicio_vigencia" 
                class="af-form-input" 
                value="{{ date('Y-m-d') }}" 
                required 
              />
            </div>

            <div class="af-form-group">
              <label for="fecha_fin_vigencia">Fecha Fin (Opcional)</label>
              <input 
                type="date" 
                id="fecha_fin_vigencia" 
                name="fecha_fin_vigencia" 
                class="af-form-input" 
              />
            </div>

            <div class="af-form-group" style="grid-column: span 2; display: flex; align-items: center; gap: 8px; margin-top: 4px;">
              <input type="checkbox" id="activa" name="activa" value="1" checked style="width: 18px; height: 18px; accent-color: var(--primary-color);" />
              <label for="activa" style="margin-bottom: 0; font-size: 14px; font-weight: 500; cursor: pointer;">
                Marcar tarifa como Activa de inmediato
              </label>
            </div>
          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-cancel" data-af-modal-close="createTarifaModal">Cancelar</button>
          <button type="submit" class="btn-save">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <span>Guardar Tarifa</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ======================================================================
       MODALES DINÁMICOS: EDITAR TARIFA
       ====================================================================== -->
  @foreach ($tarifas as $tarifa)
    <div class="af-modal-overlay" id="editTarifaModal_{{ $tarifa->id_tarifa }}">
      <div class="af-modal-card" style="max-width: 540px;">
        <div class="af-modal-header">
          <div class="af-modal-title">
            <div class="modal-title-icon amber">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
            </div>
            <div>
              <h3>Editar Tarifa: {{ $tarifa->tipo_pago }}</h3>
              <p>Modifica el valor o las fechas de vigencia</p>
            </div>
          </div>
          <button type="button" class="btn-close-modal" data-af-modal-close="editTarifaModal_{{ $tarifa->id_tarifa }}">&times;</button>
        </div>

        <form action="{{ route('tarifas.update', $tarifa->id_tarifa) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="af-modal-body">
            <div class="af-form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
              <div class="af-form-group">
                <label for="edit_tipo_{{ $tarifa->id_tarifa }}">Tipo de Tarifa <span class="req">*</span></label>
                <input 
                  type="text" 
                  id="edit_tipo_{{ $tarifa->id_tarifa }}" 
                  name="tipo_pago" 
                  class="af-form-input" 
                  value="{{ $tarifa->tipo_pago }}" 
                  required 
                />
              </div>

              <div class="af-form-group">
                <label for="edit_valor_{{ $tarifa->id_tarifa }}">Valor (COP) <span class="req">*</span></label>
                <input 
                  type="number" 
                  step="0.01" 
                  id="edit_valor_{{ $tarifa->id_tarifa }}" 
                  name="valor" 
                  class="af-form-input" 
                  value="{{ $tarifa->valor }}" 
                  required 
                />
              </div>

              <div class="af-form-group">
                <label for="edit_inicio_{{ $tarifa->id_tarifa }}">Fecha de Inicio <span class="req">*</span></label>
                <input 
                  type="date" 
                  id="edit_inicio_{{ $tarifa->id_tarifa }}" 
                  name="fecha_inicio_vigencia" 
                  class="af-form-input" 
                  value="{{ $tarifa->fecha_inicio_vigencia }}" 
                  required 
                />
              </div>

              <div class="af-form-group">
                <label for="edit_fin_{{ $tarifa->id_tarifa }}">Fecha Fin (Opcional)</label>
                <input 
                  type="date" 
                  id="edit_fin_{{ $tarifa->id_tarifa }}" 
                  name="fecha_fin_vigencia" 
                  class="af-form-input" 
                  value="{{ $tarifa->fecha_fin_vigencia }}" 
                />
              </div>

              <div class="af-form-group" style="grid-column: span 2; display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                <input 
                  type="checkbox" 
                  id="edit_activa_{{ $tarifa->id_tarifa }}" 
                  name="activa" 
                  value="1" 
                  {{ $tarifa->is_active ? 'checked' : '' }} 
                  style="width: 18px; height: 18px; accent-color: var(--primary-color);" 
                />
                <label for="edit_activa_{{ $tarifa->id_tarifa }}" style="margin-bottom: 0; font-size: 14px; font-weight: 500; cursor: pointer;">
                  Tarifa Activa
                </label>
              </div>
            </div>
          </div>

          <div class="af-modal-footer">
            <button type="button" class="btn-cancel" data-af-modal-close="editTarifaModal_{{ $tarifa->id_tarifa }}">Cancelar</button>
            <button type="submit" class="btn-save">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              <span>Guardar Cambios</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  @endforeach

  <!-- Script para Filtros en Vivo en Tabla de Tarifas -->
  @push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const liveSearch = document.getElementById('tarifaLiveSearch');
      const stateFilter = document.getElementById('tarifaStateFilter');
      const typeFilter = document.getElementById('tarifaTypeFilter');
      const tableRows = document.querySelectorAll('#tarifasTable tbody tr');

      function filterTarifas() {
        const query = (liveSearch ? liveSearch.value : '').toLowerCase().trim();
        const selectedState = (stateFilter ? stateFilter.value : 'todos').toLowerCase();
        const selectedType = (typeFilter ? typeFilter.value : 'todos').toLowerCase();

        tableRows.forEach(row => {
          if (row.querySelector('.table-empty-state')) return;

          const rowText = row.textContent.toLowerCase();
          const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
          const rowType = (row.getAttribute('data-type') || '').toLowerCase();

          const matchesQuery = !query || rowText.includes(query);
          const matchesState = (selectedState === 'todos') || (rowStatus === selectedState);
          const matchesType = (selectedType === 'todos') || (rowType === selectedType);

          if (matchesQuery && matchesState && matchesType) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      }

      if (liveSearch) liveSearch.addEventListener('input', filterTarifas);
      if (stateFilter) stateFilter.addEventListener('change', filterTarifas);
      if (typeFilter) typeFilter.addEventListener('change', filterTarifas);
    });
  </script>
  @endpush

</x-admin-layout>
