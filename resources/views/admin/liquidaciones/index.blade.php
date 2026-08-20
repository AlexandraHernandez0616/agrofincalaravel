<x-admin-layout title="Gestión de Liquidaciones">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Gestión de Liquidaciones</h1>
    <p>Genera y administra las liquidaciones de trabajadores</p>
  </x-slot>

  <!-- Botón de Acción Superior -->
  <x-slot name="actions">
    <button type="button" class="btn-primary" data-af-modal-open="createLiquidacionModal">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
      <span>+ Generar Liquidación</span>
    </button>
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
       TARJETAS KPI SUPERIORES (TOTAL, PENDIENTES, GENERADAS, LIQUIDADAS)
       ====================================================================== -->
  <div class="liquidaciones-kpi-grid">
    <!-- 1. Total Liquidaciones -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Total Liquidaciones</div>
      <div class="lotes-kpi-number">{{ $totalLiquidaciones }}</div>
    </div>

    <!-- 2. Pendientes -->
    <div class="lotes-kpi-card yellow">
      <div class="lotes-kpi-label">Pendientes</div>
      <div class="lotes-kpi-number">{{ $pendientes }}</div>
    </div>

    <!-- 3. Generadas -->
    <div class="lotes-kpi-card blue">
      <div class="lotes-kpi-label">Generadas</div>
      <div class="lotes-kpi-number">{{ $generadas }}</div>
    </div>

    <!-- 4. Liquidadas -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Liquidadas</div>
      <div class="lotes-kpi-number">{{ $liquidadas }}</div>
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
        id="liqLiveSearch" 
        class="table-search-bar-input" 
        placeholder="Buscar por trabajador o documento..." 
        value="{{ $search ?? '' }}" 
        autocomplete="off"
      />
    </div>

    <!-- Filtro de Estado -->
    <div class="table-filter-select-wrap">
      <select id="liqStateFilter" class="table-filter-select">
        <option value="todos" {{ empty($estado) || strtolower($estado) === 'todos' ? 'selected' : '' }}>Todos los estados</option>
        <option value="pendiente" {{ strtolower($estado ?? '') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        <option value="generada" {{ strtolower($estado ?? '') === 'generada' ? 'selected' : '' }}>Generada</option>
        <option value="liquidada" {{ strtolower($estado ?? '') === 'liquidada' ? 'selected' : '' }}>Liquidada</option>
      </select>
    </div>

    <!-- Filtro de Tipo de Tarifa -->
    <div class="table-filter-select-wrap">
      <select id="liqTypeFilter" class="table-filter-select">
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
       TABLA PRINCIPAL DE LIQUIDACIONES
       ====================================================================== -->
  <div class="af-table-card">
    <div class="af-table-responsive">
      <table class="af-table-data" id="liquidacionesTable">
        <thead>
          <tr>
            <th>Trabajador</th>
            <th>Tipo Tarifa</th>
            <th>Período Inicio</th>
            <th>Período Fin</th>
            <th>Jornadas</th>
            <th>Valor</th>
            <th>Estado</th>
            <th style="text-align: right; padding-right: 24px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($liquidaciones as $liq)
            <tr 
              data-status="{{ strtolower($liq->estado) }}" 
              data-type="{{ strtolower($liq->tipo_tarifa_nombre) }}"
              data-doc="{{ $liq->trabajador?->usuario?->documento ?? '' }}"
            >
              <td style="font-weight: 500; color: var(--secondary-color);">{{ $liq->trabajador_nombre }}</td>
              <td>{{ $liq->tipo_tarifa_nombre }}</td>
              <td>{{ $liq->formatted_periodo_inicio }}</td>
              <td>{{ $liq->formatted_periodo_fin }}</td>
              <td>{{ $liq->jornadas_formatted }}</td>
              <td style="font-weight: 700; color: #0f172a;">{{ $liq->formatted_valor }}</td>
              <td>
                @if ($liq->estado === 'PENDIENTE')
                  <span class="pill-status pill-status-amber">Pendiente</span>
                @elseif ($liq->estado === 'GENERADA')
                  <span class="pill-status pill-status-blue">Generada</span>
                @elseif ($liq->estado === 'LIQUIDADA')
                  <span class="pill-status pill-status-active">Liquidada</span>
                @else
                  <span class="pill-status">{{ ucfirst(strtolower($liq->estado)) }}</span>
                @endif
              </td>
              <td style="text-align: right; padding-right: 24px;">
                <div class="table-actions-cell" style="justify-content: flex-end; align-items: center; gap: 8px;">
                  <!-- Botón Ver Detalles (Icono Ojo) -->
                  <button 
                    type="button" 
                    class="action-icon-btn btn-view" 
                    title="Ver detalle de liquidación" 
                    data-af-modal-open="viewLiquidacionModal_{{ $liq->id_liquidacion }}"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>

                  <!-- Botón Editar (Icono Lápiz si no está liquidada) -->
                  @if ($liq->estado !== 'LIQUIDADA')
                    <button 
                      type="button" 
                      class="action-icon-btn btn-edit-orange" 
                      title="Editar liquidación" 
                      data-af-modal-open="editLiquidacionModal_{{ $liq->id_liquidacion }}"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    </button>
                  @endif

                  <!-- Botón Acción según Estado -->
                  @if ($liq->estado === 'PENDIENTE')
                    <!-- Generar (Pill Azul) -->
                    <form action="{{ route('liquidaciones.cambiar-estado', [$liq->id_liquidacion, 'GENERADA']) }}" method="POST" style="display: inline-block;">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="pill-btn-toggle generar" title="Generar liquidación">
                        Generar
                      </button>
                    </form>

                    <!-- Eliminar (Icono Papelera) -->
                    <form action="{{ route('liquidaciones.destroy', $liq->id_liquidacion) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar esta liquidación pendiente?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="action-icon-btn btn-delete" title="Eliminar liquidación">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                      </button>
                    </form>
                  @elseif ($liq->estado === 'GENERADA')
                    <!-- Liquidar (Pill Verde) -->
                    <form action="{{ route('liquidaciones.cambiar-estado', [$liq->id_liquidacion, 'LIQUIDADA']) }}" method="POST" style="display: inline-block;">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="pill-btn-toggle liquidar" title="Completar y liquidar pago">
                        Liquidar
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="table-empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                <p style="margin-top: 8px; color: var(--text-muted);">No hay liquidaciones registradas en este criterio.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- ======================================================================
       MODAL 1: GENERAR NUEVA LIQUIDACIÓN (COMPLETO Y DINÁMICO)
       ====================================================================== -->
  <div class="af-modal-overlay" id="createLiquidacionModal">
    <div class="af-modal-card" style="max-width: 650px;">
      <div class="af-modal-header">
        <div class="af-modal-title-wrap">
          <div class="af-modal-icon-badge green">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <div>
            <div class="af-modal-title">Generar Liquidación de Pago</div>
            <div class="af-modal-subtitle">Calcula y genera la liquidación laboral por jornadas o recolección</div>
          </div>
        </div>
        <button type="button" class="af-modal-close-btn" data-af-modal-close="createLiquidacionModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
        </button>
      </div>

      <form action="{{ route('liquidaciones.store') }}" method="POST" id="newLiquidacionForm">
        @csrf
        <div class="af-modal-body">
          <div class="af-form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
            
            <!-- 1. Selección de Trabajador -->
            <div class="af-form-group" style="grid-column: span 2;">
              <label for="new_id_trabajador">Trabajador Beneficiario <span class="req">*</span></label>
              <select id="new_id_trabajador" name="id_trabajador" class="af-form-input" required>
                <option value="" disabled selected>Selecciona un trabajador activo</option>
                @foreach ($trabajadores as $t)
                  <option 
                    value="{{ $t->id_trabajador }}"
                    data-nombre="{{ $t->usuario?->name ?? 'Trabajador' }}"
                    data-doc="{{ $t->usuario?->documento ?? '-' }}"
                    data-tel="{{ $t->usuario?->telefono ?? '-' }}"
                    data-eps="{{ $t->eps ?? 'N/A' }}"
                    data-rh="{{ $t->rh ?? 'N/A' }}"
                  >
                    {{ $t->usuario?->name ?? 'Trabajador' }} — Documento: {{ $t->usuario?->documento ?? '-' }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Badge Informativo del Trabajador Seleccionado -->
            <div id="workerInfoBadge" class="worker-preview-badge" style="grid-column: span 2; display: none;">
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                  <strong>Documento:</strong> <span id="badgeDoc">-</span> | 
                  <strong>Teléfono:</strong> <span id="badgeTel">-</span>
                </div>
                <div>
                  <span class="pill-status pill-status-active" style="font-size: 11px;">
                    EPS: <span id="badgeEps">-</span> (<span id="badgeRh">-</span>)
                  </span>
                </div>
              </div>
            </div>

            <!-- 2. Selección de Tarifa -->
            <div class="af-form-group" style="grid-column: span 2;">
              <label for="new_id_tarifa">Tipo de Tarifa / Labor <span class="req">*</span></label>
              <select id="new_id_tarifa" name="id_tarifa" class="af-form-input" required>
                <option value="" disabled selected>Selecciona una tarifa vigente</option>
                @foreach ($tarifas as $tarifa)
                  <option 
                    value="{{ $tarifa->id_tarifa }}" 
                    data-valor="{{ $tarifa->valor }}" 
                    data-tipo="{{ strtolower($tarifa->tipo_pago) }}"
                    data-label="{{ $tarifa->tipo_pago }}"
                  >
                    {{ $tarifa->tipo_pago }} — {{ $tarifa->formatted_valor }} (Vigente desde {{ $tarifa->formatted_fecha_inicio }})
                  </option>
                @endforeach
              </select>
            </div>

            <!-- 3. Período Inicio -->
            <div class="af-form-group">
              <label for="new_periodo_inicio">Fecha Inicio Período <span class="req">*</span></label>
              <input 
                type="date" 
                id="new_periodo_inicio" 
                name="periodo_inicio" 
                class="af-form-input" 
                value="{{ date('Y-m-d') }}" 
                required 
              />
            </div>

            <!-- 4. Período Fin -->
            <div class="af-form-group">
              <label for="new_periodo_fin">Fecha Fin Período <span class="req">*</span></label>
              <input 
                type="date" 
                id="new_periodo_fin" 
                name="periodo_fin" 
                class="af-form-input" 
                value="{{ date('Y-m-d') }}" 
                required 
              />
            </div>

            <!-- 5. Jornadas Consideradas -->
            <div class="af-form-group" id="groupJornadas">
              <label for="new_jornadas">
                Jornadas / Días Trabajados 
                <span style="font-size: 12px; color: var(--text-muted);">(Días)</span>
              </label>
              <input 
                type="number" 
                step="0.5" 
                id="new_jornadas" 
                name="jornadas_consideradas" 
                class="af-form-input" 
                value="1" 
                min="0" 
              />
            </div>

            <!-- 6. Producción Considerada -->
            <div class="af-form-group" id="groupProduccion">
              <label for="new_produccion">
                Producción Recolectada 
                <span style="font-size: 12px; color: var(--text-muted);">(Kg / Unidades)</span>
              </label>
              <input 
                type="number" 
                step="0.01" 
                id="new_produccion" 
                name="produccion_considerada" 
                class="af-form-input" 
                placeholder="0.00" 
                min="0" 
              />
            </div>

            <!-- 7. Tarjeta Resumen y Cálculo en Vivo -->
            <div class="af-calc-summary-card" style="grid-column: span 2;">
              <div class="af-calc-summary-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><line x1="8" x2="16" y1="12" y2="12"/><line x1="12" x2="12" y1="8" y2="16"/></svg>
                <span>Desglose de Liquidación en Tiempo Real</span>
              </div>
              <div class="af-calc-breakdown-row">
                <span>Tarifa Base Seleccionada:</span>
                <strong id="calcRateLabel">$0 COP</strong>
              </div>
              <div class="af-calc-breakdown-row">
                <span id="calcUnitsLabel">Unidades a liquidar:</span>
                <strong id="calcUnitsCount">1.0 jornada(s)</strong>
              </div>
              <div class="af-calc-total-row">
                <div class="af-calc-total-label">Total a Liquidar:</div>
                <div class="af-calc-total-amount" id="calcTotalDisplay">$0 COP</div>
              </div>
            </div>

            <!-- 8. Valor Total a Liquidar (Input sincronizado) -->
            <div class="af-form-group" style="grid-column: span 2;">
              <label for="new_valor_calculado">Valor Neto a Liquidar (COP) <span class="req">*</span></label>
              <div class="af-input-wrapper">
                <input 
                  type="number" 
                  step="0.01" 
                  id="new_valor_calculado" 
                  name="valor_calculado" 
                  class="af-form-input" 
                  placeholder="0.00" 
                  required 
                />
              </div>
              <span style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">
                💡 Se calcula automáticamente según la tarifa y unidades, pero puedes ajustarlo si aplica bonificación o deducción.
              </span>
            </div>

            <!-- 9. Estado Inicial -->
            <div class="af-form-group" style="grid-column: span 2;">
              <label for="new_estado">Estado Inicial de la Liquidación</label>
              <select id="new_estado" name="estado" class="af-form-input">
                <option value="GENERADA" selected>Generada (Listo para liquidar / pagar)</option>
                <option value="PENDIENTE">Pendiente (Requiere revisión previa)</option>
                <option value="LIQUIDADA">Liquidada (Pago efectuado de inmediato)</option>
              </select>
            </div>

            <!-- 10. Observación y Chips Rápidos -->
            <div class="af-form-group" style="grid-column: span 2;">
              <label for="new_observacion">Observaciones / Concepto de Pago</label>
              <textarea 
                id="new_observacion" 
                name="observacion" 
                rows="2" 
                class="af-form-input" 
                placeholder="Ingresa detalles sobre las labores o selecciona un concepto rápido abajo..."
              ></textarea>
              
              <!-- Chips Rápidos -->
              <div class="concept-chips-wrap">
                <span class="concept-chip" onclick="addConcept('Jornadas de recolección de café.')">☕ Cosecha de Café</span>
                <span class="concept-chip" onclick="addConcept('Recolección y beneficio de cacao.')">🍫 Cosecha de Cacao</span>
                <span class="concept-chip" onclick="addConcept('Jornadas de desyerbe y mantenimiento de lote.')">🌿 Desyerbe y Mantenimiento</span>
                <span class="concept-chip" onclick="addConcept('Poda de formación y fertilización de suelo.')">✂️ Poda y Fertilización</span>
                <span class="concept-chip" onclick="addConcept('Jornada general de adecuación de finca.')">🚜 Labores Generales</span>
              </div>
            </div>

          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-cancel" data-af-modal-close="createLiquidacionModal">Cancelar</button>
          <button type="submit" class="btn-save">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <span>Crear Liquidación</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ======================================================================
       MODALES DINÁMICOS: EDITAR LIQUIDACIÓN
       ====================================================================== -->
  @foreach ($liquidaciones as $liq)
    <div class="af-modal-overlay" id="editLiquidacionModal_{{ $liq->id_liquidacion }}">
      <div class="af-modal-card" style="max-width: 600px;">
        <div class="af-modal-header">
          <div class="af-modal-title-wrap">
            <div class="af-modal-icon-badge amber">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
            </div>
            <div>
              <div class="af-modal-title">Editar Liquidación #{{ $liq->id_liquidacion }}</div>
              <div class="af-modal-subtitle">Trabajador: <strong>{{ $liq->trabajador_nombre }}</strong></div>
            </div>
          </div>
          <button type="button" class="af-modal-close-btn" data-af-modal-close="editLiquidacionModal_{{ $liq->id_liquidacion }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
          </button>
        </div>

        <form action="{{ route('liquidaciones.update', $liq->id_liquidacion) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="af-modal-body">
            <div class="af-form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
              <div class="af-form-group" style="grid-column: span 2;">
                <label for="edit_trabajador_{{ $liq->id_liquidacion }}">Trabajador <span class="req">*</span></label>
                <select id="edit_trabajador_{{ $liq->id_liquidacion }}" name="id_trabajador" class="af-form-input" required>
                  @foreach ($trabajadores as $t)
                    <option value="{{ $t->id_trabajador }}" {{ $liq->id_trabajador == $t->id_trabajador ? 'selected' : '' }}>
                      {{ $t->usuario?->name ?? 'Trabajador' }} (Doc: {{ $t->usuario?->documento ?? '-' }})
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="af-form-group" style="grid-column: span 2;">
                <label for="edit_tarifa_{{ $liq->id_liquidacion }}">Tarifa Aplicada <span class="req">*</span></label>
                <select id="edit_tarifa_{{ $liq->id_liquidacion }}" name="id_tarifa" class="af-form-input" required>
                  @foreach ($tarifas as $tarifa)
                    <option value="{{ $tarifa->id_tarifa }}" {{ $liq->id_tarifa == $tarifa->id_tarifa ? 'selected' : '' }}>
                      {{ $tarifa->tipo_pago }} - {{ $tarifa->formatted_valor }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="af-form-group">
                <label for="edit_ini_{{ $liq->id_liquidacion }}">Período Inicio <span class="req">*</span></label>
                <input type="date" id="edit_ini_{{ $liq->id_liquidacion }}" name="periodo_inicio" class="af-form-input" value="{{ $liq->periodo_inicio }}" required />
              </div>

              <div class="af-form-group">
                <label for="edit_fin_{{ $liq->id_liquidacion }}">Período Fin <span class="req">*</span></label>
                <input type="date" id="edit_fin_{{ $liq->id_liquidacion }}" name="periodo_fin" class="af-form-input" value="{{ $liq->periodo_fin }}" required />
              </div>

              <div class="af-form-group">
                <label for="edit_jor_{{ $liq->id_liquidacion }}">Jornadas</label>
                <input type="number" step="0.5" id="edit_jor_{{ $liq->id_liquidacion }}" name="jornadas_consideradas" class="af-form-input" value="{{ $liq->jornadas_consideradas }}" />
              </div>

              <div class="af-form-group">
                <label for="edit_prod_{{ $liq->id_liquidacion }}">Producción (kg)</label>
                <input type="number" step="0.01" id="edit_prod_{{ $liq->id_liquidacion }}" name="produccion_considerada" class="af-form-input" value="{{ $liq->produccion_considerada }}" />
              </div>

              <div class="af-form-group" style="grid-column: span 2;">
                <label for="edit_val_{{ $liq->id_liquidacion }}">Valor a Pagar (COP) <span class="req">*</span></label>
                <input type="number" step="0.01" id="edit_val_{{ $liq->id_liquidacion }}" name="valor_calculado" class="af-form-input" value="{{ $liq->valor_calculado }}" required />
              </div>

              <div class="af-form-group" style="grid-column: span 2;">
                <label for="edit_est_{{ $liq->id_liquidacion }}">Estado</label>
                <select id="edit_est_{{ $liq->id_liquidacion }}" name="estado" class="af-form-input">
                  <option value="PENDIENTE" {{ $liq->estado === 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                  <option value="GENERADA" {{ $liq->estado === 'GENERADA' ? 'selected' : '' }}>Generada</option>
                  <option value="LIQUIDADA" {{ $liq->estado === 'LIQUIDADA' ? 'selected' : '' }}>Liquidada</option>
                </select>
              </div>

              <div class="af-form-group" style="grid-column: span 2;">
                <label for="edit_obs_{{ $liq->id_liquidacion }}">Observación</label>
                <textarea id="edit_obs_{{ $liq->id_liquidacion }}" name="observacion" rows="2" class="af-form-input">{{ $liq->observacion }}</textarea>
              </div>
            </div>
          </div>

          <div class="af-modal-footer">
            <button type="button" class="btn-cancel" data-af-modal-close="editLiquidacionModal_{{ $liq->id_liquidacion }}">Cancelar</button>
            <button type="submit" class="btn-save">
              <span>Guardar Cambios</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  @endforeach

  <!-- ======================================================================
       MODALES DINÁMICOS: VER DETALLES DE LIQUIDACIÓN
       ====================================================================== -->
  @foreach ($liquidaciones as $liq)
    <div class="af-modal-overlay" id="viewLiquidacionModal_{{ $liq->id_liquidacion }}">
      <div class="af-modal-card" style="max-width: 560px;">
        <div class="af-modal-header">
          <div class="af-modal-title-wrap">
            <div class="af-modal-icon-badge blue">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div>
              <div class="af-modal-title">Detalle de Liquidación #{{ $liq->id_liquidacion }}</div>
              <div class="af-modal-subtitle">Trabajador: <strong>{{ $liq->trabajador_nombre }}</strong></div>
            </div>
          </div>
          <button type="button" class="af-modal-close-btn" data-af-modal-close="viewLiquidacionModal_{{ $liq->id_liquidacion }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
          </button>
        </div>

        <div class="af-modal-body">
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 18px; margin-bottom: 16px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13.5px;">
              <div>
                <span style="color: var(--text-muted);">Documento:</span>
                <div style="font-weight: 600; color: #0f172a;">{{ $liq->trabajador?->usuario?->documento ?? '-' }}</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Teléfono:</span>
                <div style="font-weight: 600; color: #0f172a;">{{ $liq->trabajador?->usuario?->telefono ?? '-' }}</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Tarifa Aplicada:</span>
                <div style="font-weight: 600; color: #0f172a;">{{ $liq->tipo_tarifa_nombre }} ({{ $liq->tarifa?->formatted_valor ?? '$0' }})</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Estado Actual:</span>
                <div>
                  @if ($liq->estado === 'PENDIENTE')
                    <span class="pill-status pill-status-amber">Pendiente</span>
                  @elseif ($liq->estado === 'GENERADA')
                    <span class="pill-status pill-status-blue">Generada</span>
                  @elseif ($liq->estado === 'LIQUIDADA')
                    <span class="pill-status pill-status-active">Liquidada</span>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px 18px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13.5px;">
              <span style="color: #166534;">Período Liquidado:</span>
              <strong style="color: #14532d;">{{ $liq->formatted_periodo_inicio }} al {{ $liq->formatted_periodo_fin }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13.5px;">
              <span style="color: #166534;">Jornadas Calculadas:</span>
              <strong style="color: #14532d;">{{ $liq->jornadas_formatted }} jornadas</strong>
            </div>
            @if ($liq->produccion_considerada > 0)
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13.5px;">
                <span style="color: #166534;">Producción Registrada:</span>
                <strong style="color: #14532d;">{{ number_format($liq->produccion_considerada, 2) }} kg</strong>
              </div>
            @endif
            <div style="height: 1px; background: #bbf7d0; margin: 10px 0;"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 16px;">
              <span style="color: #166534; font-weight: 700;">Monto a Pagar:</span>
              <span style="font-size: 20px; font-weight: 800; color: #15803d;">{{ $liq->formatted_valor }}</span>
            </div>
          </div>

          @if (!empty($liq->observacion))
            <div style="font-size: 13px; color: var(--text-muted);">
              <strong>Observaciones:</strong>
              <p style="margin-top: 2px;">{{ $liq->observacion }}</p>
            </div>
          @endif
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-cancel" data-af-modal-close="viewLiquidacionModal_{{ $liq->id_liquidacion }}">Cerrar</button>
          @if ($liq->estado === 'PENDIENTE')
            <form action="{{ route('liquidaciones.cambiar-estado', [$liq->id_liquidacion, 'GENERADA']) }}" method="POST" style="display: inline;">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn-save" style="background: #2563eb;">
                <span>Generar para Pago</span>
              </button>
            </form>
          @elseif ($liq->estado === 'GENERADA')
            <form action="{{ route('liquidaciones.cambiar-estado', [$liq->id_liquidacion, 'LIQUIDADA']) }}" method="POST" style="display: inline;">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn-save">
                <span>Liquidar y Pagar</span>
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>
  @endforeach

  <!-- ======================================================================
       SCRIPTS: FILTROS EN TIEMPO REAL Y AUTO-CÁLCULO EN FORMULARIO
       ====================================================================== -->
  @push('scripts')
  <script>
    function formatCOP(num) {
      return '$' + Number(num).toLocaleString('en-US');
    }

    function addConcept(text) {
      const obs = document.getElementById('new_observacion');
      if (obs) {
        if (obs.value.trim() === '') {
          obs.value = text;
        } else {
          obs.value += ' ' + text;
        }
      }
    }

    document.addEventListener("DOMContentLoaded", function() {
      // 1. Filtros en Vivo en Tabla
      const liveSearch = document.getElementById('liqLiveSearch');
      const stateFilter = document.getElementById('liqStateFilter');
      const typeFilter = document.getElementById('liqTypeFilter');
      const tableRows = document.querySelectorAll('#liquidacionesTable tbody tr');

      function filterLiquidaciones() {
        const query = (liveSearch ? liveSearch.value : '').toLowerCase().trim();
        const selectedState = (stateFilter ? stateFilter.value : 'todos').toLowerCase();
        const selectedType = (typeFilter ? typeFilter.value : 'todos').toLowerCase();

        tableRows.forEach(row => {
          if (row.querySelector('.table-empty-state')) return;

          const rowText = row.textContent.toLowerCase();
          const rowDoc = (row.getAttribute('data-doc') || '').toLowerCase();
          const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
          const rowType = (row.getAttribute('data-type') || '').toLowerCase();

          const matchesQuery = !query || rowText.includes(query) || rowDoc.includes(query);
          const matchesState = (selectedState === 'todos') || (rowStatus === selectedState);
          const matchesType = (selectedType === 'todos') || (rowType === selectedType);

          if (matchesQuery && matchesState && matchesType) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      }

      if (liveSearch) liveSearch.addEventListener('input', filterLiquidaciones);
      if (stateFilter) stateFilter.addEventListener('change', filterLiquidaciones);
      if (typeFilter) typeFilter.addEventListener('change', filterLiquidaciones);

      // 2. Información dinámica del trabajador
      const workerSelect = document.getElementById('new_id_trabajador');
      const workerInfoBadge = document.getElementById('workerInfoBadge');
      const badgeDoc = document.getElementById('badgeDoc');
      const badgeTel = document.getElementById('badgeTel');
      const badgeEps = document.getElementById('badgeEps');
      const badgeRh = document.getElementById('badgeRh');

      if (workerSelect) {
        workerSelect.addEventListener('change', function() {
          const opt = workerSelect.options[workerSelect.selectedIndex];
          if (opt && opt.dataset.doc) {
            badgeDoc.textContent = opt.dataset.doc;
            badgeTel.textContent = opt.dataset.tel;
            badgeEps.textContent = opt.dataset.eps;
            badgeRh.textContent = opt.dataset.rh;
            workerInfoBadge.style.display = 'block';
          } else {
            workerInfoBadge.style.display = 'none';
          }
        });
      }

      // 3. Auto-cálculo y visualización interactiva de liquidación
      const tarifaSelect = document.getElementById('new_id_tarifa');
      const jornadasInput = document.getElementById('new_jornadas');
      const produccionInput = document.getElementById('new_produccion');
      const valorInput = document.getElementById('new_valor_calculado');
      const calcRateLabel = document.getElementById('calcRateLabel');
      const calcUnitsLabel = document.getElementById('calcUnitsLabel');
      const calcUnitsCount = document.getElementById('calcUnitsCount');
      const calcTotalDisplay = document.getElementById('calcTotalDisplay');

      function autoCalculateValor() {
        if (!tarifaSelect || !valorInput) return;
        const selectedOption = tarifaSelect.options[tarifaSelect.selectedIndex];
        
        if (!selectedOption || !selectedOption.dataset.valor) {
          calcRateLabel.textContent = '$0 COP';
          calcTotalDisplay.textContent = '$0 COP';
          return;
        }

        const rateValue = parseFloat(selectedOption.dataset.valor) || 0;
        const rateTipo = (selectedOption.dataset.tipo || '').toLowerCase();
        const jornadas = parseFloat(jornadasInput.value) || 0;
        const produccion = parseFloat(produccionInput.value) || 0;

        calcRateLabel.textContent = formatCOP(rateValue) + ' COP (' + (selectedOption.dataset.label || 'Tarifa') + ')';

        let totalCalculado = 0;
        if (produccion > 0) {
          totalCalculado = produccion * rateValue;
          calcUnitsLabel.textContent = 'Producción recolectada:';
          calcUnitsCount.textContent = produccion.toFixed(2) + ' kg/unidades';
        } else {
          totalCalculado = jornadas * rateValue;
          calcUnitsLabel.textContent = 'Jornadas calculadas:';
          calcUnitsCount.textContent = jornadas.toFixed(1) + ' jornada(s)';
        }

        valorInput.value = totalCalculado.toFixed(0);
        calcTotalDisplay.textContent = formatCOP(totalCalculado) + ' COP';
      }

      if (tarifaSelect) tarifaSelect.addEventListener('change', autoCalculateValor);
      if (jornadasInput) jornadasInput.addEventListener('input', autoCalculateValor);
      if (produccionInput) produccionInput.addEventListener('input', autoCalculateValor);
      if (valorInput) {
        valorInput.addEventListener('input', function() {
          const val = parseFloat(valorInput.value) || 0;
          calcTotalDisplay.textContent = formatCOP(val) + ' COP (Manual)';
        });
      }
    });
  </script>
  @endpush

</x-admin-layout>
