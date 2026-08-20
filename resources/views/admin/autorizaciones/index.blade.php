<x-admin-layout title="Gestión de Liquidaciones Temporales">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Gestión de Liquidaciones Temporales</h1>
    <p>Otorga y controla permisos temporales para que los Mayordomos puedan realizar liquidaciones autorizadas</p>
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
       TARJETAS KPI SUPERIORES (TOTAL, ACTIVOS, EXPIRADOS, REVOCADOS)
       ====================================================================== -->
  <div class="autorizaciones-kpi-grid">
    <!-- 1. Total Permisos -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Total Permisos</div>
      <div class="lotes-kpi-number">{{ $totalPermisos }}</div>
    </div>

    <!-- 2. Activos -->
    <div class="lotes-kpi-card blue">
      <div class="lotes-kpi-label">Activos</div>
      <div class="lotes-kpi-number">{{ $activos }}</div>
    </div>

    <!-- 3. Expirados -->
    <div class="lotes-kpi-card yellow">
      <div class="lotes-kpi-label">Expirados</div>
      <div class="lotes-kpi-number">{{ $expirados }}</div>
    </div>

    <!-- 4. Revocados -->
    <div class="lotes-kpi-card red">
      <div class="lotes-kpi-label">Revocados</div>
      <div class="lotes-kpi-number">{{ $revocados }}</div>
    </div>
  </div>

  <!-- ======================================================================
       SECCIÓN PRINCIPAL: PERMISOS OTORGADOS
       ====================================================================== -->
  <div class="af-table-card" style="padding-top: 20px;">
    
    <!-- Encabezado de la Sección de Permisos -->
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 24px 16px 24px;">
      <h3 style="font-size: 18px; font-weight: 700; color: var(--secondary-color); margin: 0;">Permisos Otorgados</h3>
      <button type="button" class="btn-primary" data-af-modal-open="createAutorizacionModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
        <span>+ Otorgar Permiso</span>
      </button>
    </div>

    <!-- Barra de Búsqueda y Filtros en Tiempo Real -->
    <div class="table-toolbar-row" style="padding: 0 24px 16px 24px;">
      <div class="table-search-bar-wrap" style="flex: 1;">
        <span class="search-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </span>
        <input 
          type="text" 
          id="autLiveSearch" 
          class="table-search-bar-input" 
          placeholder="Buscar por mayordomo..." 
          value="{{ $search ?? '' }}" 
          autocomplete="off"
        />
      </div>

      <!-- Filtro de Estado -->
      <div class="table-filter-select-wrap">
        <select id="autStateFilter" class="table-filter-select">
          <option value="todos" {{ empty($estado) || strtolower($estado) === 'todos' ? 'selected' : '' }}>Todos los estados</option>
          <option value="activa" {{ strtolower($estado ?? '') === 'activa' ? 'selected' : '' }}>Activo</option>
          <option value="vencida" {{ strtolower($estado ?? '') === 'vencida' ? 'selected' : '' }}>Vencido / Expirado</option>
          <option value="revocada" {{ strtolower($estado ?? '') === 'revocada' ? 'selected' : '' }}>Revocado</option>
        </select>
      </div>
    </div>

    <!-- Tabla Principal de Autorizaciones -->
    <div class="af-table-responsive">
      <table class="af-table-data" id="autorizacionesTable">
        <thead>
          <tr>
            <th>Mayordomo autorizado</th>
            <th>Fecha inicio</th>
            <th>Fecha fin</th>
            <th>Estado</th>
            <th>Liquidaciones</th>
            <th>Autorizado por</th>
            <th style="text-align: right; padding-right: 24px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($autorizaciones as $aut)
            <tr 
              data-status="{{ strtolower($aut->estado) }}" 
              data-mayordomo="{{ strtolower($aut->mayordomo_nombre) }}"
            >
              <td style="font-weight: 600; color: var(--secondary-color);">{{ $aut->mayordomo_nombre }}</td>
              <td>{{ $aut->formatted_fecha_inicio }}</td>
              <td>{{ $aut->formatted_fecha_fin }}</td>
              <td>
                @if (strtoupper($aut->estado) === 'ACTIVA')
                  <span class="pill-status pill-status-active">🟢 Activo</span>
                @elseif (strtoupper($aut->estado) === 'VENCIDA')
                  <span class="pill-status pill-status-gray">⏱️ Vencido</span>
                @elseif (strtoupper($aut->estado) === 'REVOCADA')
                  <span class="pill-status pill-status-red">🚫 Revocado</span>
                @else
                  <span class="pill-status">{{ ucfirst(strtolower($aut->estado)) }}</span>
                @endif
              </td>
              <td style="font-weight: 600; color: #0f172a;">{{ $aut->liquidaciones_count ?? $aut->liquidaciones->count() }}</td>
              <td style="color: var(--text-muted);">{{ $aut->administrador_nombre }}</td>
              <td style="text-align: right; padding-right: 24px;">
                <!-- Botón Ver Registros (Diseño exacto estilo AgroFinca) -->
                <button 
                  type="button" 
                  class="btn-ver-detalles" 
                  title="Ver registros de esta autorización"
                  data-af-modal-open="viewAutorizacionModal_{{ $aut->id_autorizacion }}"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  <span>Ver registros</span>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="table-empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                <p style="margin-top: 8px; color: var(--text-muted);">No hay permisos temporales otorgados actualmente.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- ======================================================================
       MODAL 1: OTORGAR PERMISO TEMPORAL A MAYORDOMO
       ====================================================================== -->
  <div class="af-modal-overlay" id="createAutorizacionModal">
    <div class="af-modal-card" style="max-width: 600px;">
      <div class="af-modal-header">
        <div class="af-modal-title-wrap">
          <div class="af-modal-icon-badge green">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
          </div>
          <div>
            <div class="af-modal-title">Otorgar Permiso Temporal</div>
            <div class="af-modal-subtitle">Habilita al Mayordomo para generar y registrar liquidaciones</div>
          </div>
        </div>
        <button type="button" class="af-modal-close-btn" data-af-modal-close="createAutorizacionModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
        </button>
      </div>

      <form action="{{ route('autorizaciones.store') }}" method="POST">
        @csrf
        <div class="af-modal-body">
          <div class="af-form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
            
            <!-- 1. Selección de Mayordomo -->
            <div class="af-form-group" style="grid-column: span 2;">
              <label for="new_id_mayordomo">Mayordomo a Autorizar <span class="req">*</span></label>
              <select id="new_id_mayordomo" name="id_mayordomo" class="af-form-input" required>
                <option value="" disabled selected>Selecciona un mayordomo activo</option>
                @foreach ($mayordomos as $m)
                  <option 
                    value="{{ $m->id_usuario }}"
                    data-nombre="{{ $m->name }}"
                    data-doc="{{ $m->documento ?? '-' }}"
                    data-tel="{{ $m->telefono ?? '-' }}"
                  >
                    {{ $m->name }} — Documento: {{ $m->documento ?? '-' }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Badge Informativo del Mayordomo -->
            <div id="mayordomoInfoBadge" class="worker-preview-badge" style="grid-column: span 2; display: none;">
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                  <strong>Documento:</strong> <span id="badgeMDoc">-</span> | 
                  <strong>Teléfono:</strong> <span id="badgeMTel">-</span>
                </div>
                <div>
                  <span class="pill-status pill-status-active" style="font-size: 11px;">Rol: Mayordomo</span>
                </div>
              </div>
            </div>

            <!-- 2. Fecha Inicio -->
            <div class="af-form-group">
              <label for="new_fecha_inicio">Fecha Inicio Vigencia <span class="req">*</span></label>
              <input 
                type="date" 
                id="new_fecha_inicio" 
                name="fecha_inicio" 
                class="af-form-input" 
                value="{{ date('Y-m-d') }}" 
                required 
              />
            </div>

            <!-- 3. Fecha Fin -->
            <div class="af-form-group">
              <label for="new_fecha_fin">Fecha Fin Vigencia <span class="req">*</span></label>
              <input 
                type="date" 
                id="new_fecha_fin" 
                name="fecha_fin" 
                class="af-form-input" 
                value="{{ date('Y-m-d', strtotime('+3 days')) }}" 
                required 
              />
            </div>

            <!-- 4. Acciones Permitidas -->
            <div class="af-form-group" style="grid-column: span 2;">
              <label for="new_acciones">Acciones / Módulos Permitidos <span class="req">*</span></label>
              <input 
                type="text" 
                id="new_acciones" 
                name="acciones_permitidas" 
                class="af-form-input" 
                value="Liquidaciones de Pago y Cosecha" 
                list="acciones_sugeridas"
                required 
              />
              <datalist id="acciones_sugeridas">
                <option value="Liquidaciones de Pago y Cosecha">
                <option value="Liquidaciones de Pago">
                <option value="Gestión de Nómina y Pagos">
                <option value="Cosecha y Liquidación Temporal">
              </datalist>
            </div>

            <!-- 5. Monto Máximo (Opcional) -->
            <div class="af-form-group" style="grid-column: span 2;">
              <label for="new_monto_maximo">Monto Máximo Autorizado en Pesos (Opcional)</label>
              <input 
                type="number" 
                step="0.01" 
                id="new_monto_maximo" 
                name="monto_maximo" 
                class="af-form-input" 
                placeholder="Ej: 1000000 (Dejar vacío para sin límite)" 
              />
            </div>

          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-cancel" data-af-modal-close="createAutorizacionModal">Cancelar</button>
          <button type="submit" class="btn-save">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <span>Otorgar Permiso</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ======================================================================
       MODALES DINÁMICOS: VER DETALLES Y REGISTROS DE AUTORIZACIÓN
       ====================================================================== -->
  @foreach ($autorizaciones as $aut)
    <div class="af-modal-overlay" id="viewAutorizacionModal_{{ $aut->id_autorizacion }}">
      <div class="af-modal-card" style="max-width: 600px;">
        <div class="af-modal-header">
          <div class="af-modal-title-wrap">
            <div class="af-modal-icon-badge blue">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            </div>
            <div>
              <div class="af-modal-title">Autorización Delegada #{{ $aut->id_autorizacion }}</div>
              <div class="af-modal-subtitle">Mayordomo: <strong>{{ $aut->mayordomo_nombre }}</strong></div>
            </div>
          </div>
          <button type="button" class="af-modal-close-btn" data-af-modal-close="viewAutorizacionModal_{{ $aut->id_autorizacion }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
          </button>
        </div>

        <div class="af-modal-body">
          <!-- Información General del Permiso -->
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 18px; margin-bottom: 16px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13.5px;">
              <div>
                <span style="color: var(--text-muted);">Mayordomo:</span>
                <div style="font-weight: 600; color: #0f172a;">{{ $aut->mayordomo_nombre }}</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Autorizado Por:</span>
                <div style="font-weight: 600; color: #0f172a;">{{ $aut->administrador_nombre }}</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Período Vigencia:</span>
                <div style="font-weight: 600; color: #1e40af;">{{ $aut->formatted_fecha_inicio }} al {{ $aut->formatted_fecha_fin }}</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Estado del Permiso:</span>
                <div>
                  @if (strtoupper($aut->estado) === 'ACTIVA')
                    <span class="pill-status pill-status-active">🟢 Activo</span>
                  @elseif (strtoupper($aut->estado) === 'VENCIDA')
                    <span class="pill-status pill-status-gray">⏱️ Vencido</span>
                  @elseif (strtoupper($aut->estado) === 'REVOCADA')
                    <span class="pill-status pill-status-red">🚫 Revocado</span>
                  @endif
                </div>
              </div>
              <div style="grid-column: span 2;">
                <span style="color: var(--text-muted);">Acciones Autorizadas:</span>
                <div style="font-weight: 600; color: #0f172a;">{{ $aut->acciones_permitidas }}</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Monto Máximo:</span>
                <div style="font-weight: 600; color: #166534;">{{ $aut->formatted_monto_maximo }}</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Liquidaciones Registradas:</span>
                <div style="font-weight: 700; color: #0f172a;">{{ $aut->liquidaciones_count ?? $aut->liquidaciones->count() }} operaciones</div>
              </div>
            </div>
          </div>

          <!-- Historial de Operaciones Realizadas -->
          <div>
            <h4 style="font-size: 14px; font-weight: 700; color: var(--secondary-color); margin-bottom: 8px;">Liquidaciones Realizadas bajo este Permiso:</h4>
            @if ($aut->liquidaciones->count() > 0)
              <div style="border: 1px solid var(--border); border-radius: 10px; overflow: hidden;">
                <table style="width: 100%; font-size: 12.5px; border-collapse: collapse;">
                  <thead style="background: #f1f5f9; text-align: left;">
                    <tr>
                      <th style="padding: 8px 12px;">ID</th>
                      <th style="padding: 8px 12px;">Trabajador</th>
                      <th style="padding: 8px 12px;">Valor</th>
                      <th style="padding: 8px 12px;">Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($aut->liquidaciones as $lq)
                      <tr style="border-top: 1px solid #e2e8f0;">
                        <td style="padding: 8px 12px; font-weight: 600;">LIQ-{{ str_pad($lq->id_liquidacion, 3, '0', STR_PAD_LEFT) }}</td>
                        <td style="padding: 8px 12px;">{{ $lq->trabajador_nombre }}</td>
                        <td style="padding: 8px 12px; font-weight: 600; color: #166534;">{{ $lq->formatted_valor }}</td>
                        <td style="padding: 8px 12px;">
                          <span class="pill-status pill-status-active" style="font-size: 11px;">{{ $lq->estado }}</span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div style="background: #f8fafc; padding: 14px; border-radius: 10px; text-align: center; color: var(--text-muted); font-size: 13px;">
                No se han registrado liquidaciones vinculadas a esta autorización aún.
              </div>
            @endif
          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-cancel" data-af-modal-close="viewAutorizacionModal_{{ $aut->id_autorizacion }}">Cerrar</button>

          <!-- Botón de Revocar si está Activo -->
          @if (strtoupper($aut->estado) === 'ACTIVA')
            <form action="{{ route('autorizaciones.revocar', $aut->id_autorizacion) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de revocar este permiso temporal?');">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn-save" style="background: #dc2626;">
                <span>Revocar Permiso</span>
              </button>
            </form>
          @else
            <!-- Reactivar si está Vencido o Revocado -->
            <form action="{{ route('autorizaciones.reactivar', $aut->id_autorizacion) }}" method="POST" style="display: inline;">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn-save">
                <span>Reactivar Permiso</span>
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>
  @endforeach

  <!-- ======================================================================
       SCRIPTS: FILTROS EN VIVO Y AUTO-RELLENO DE MAYORDOMO
       ====================================================================== -->
  @push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // 1. Filtros en Vivo en Tabla de Autorizaciones
      const liveSearch = document.getElementById('autLiveSearch');
      const stateFilter = document.getElementById('autStateFilter');
      const tableRows = document.querySelectorAll('#autorizacionesTable tbody tr');

      function filterAutorizaciones() {
        const query = (liveSearch ? liveSearch.value : '').toLowerCase().trim();
        const selectedState = (stateFilter ? stateFilter.value : 'todos').toLowerCase();

        tableRows.forEach(row => {
          if (row.querySelector('.table-empty-state')) return;

          const rowText = row.textContent.toLowerCase();
          const rowMayordomo = (row.getAttribute('data-mayordomo') || '').toLowerCase();
          const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();

          const matchesQuery = !query || rowText.includes(query) || rowMayordomo.includes(query);
          const matchesState = (selectedState === 'todos') || (rowStatus === selectedState);

          if (matchesQuery && matchesState) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      }

      if (liveSearch) liveSearch.addEventListener('input', filterAutorizaciones);
      if (stateFilter) stateFilter.addEventListener('change', filterAutorizaciones);

      // 2. Info preview de Mayordomo al seleccionarlo en el Modal
      const mayordomoSelect = document.getElementById('new_id_mayordomo');
      const mayordomoBadge = document.getElementById('mayordomoInfoBadge');
      const badgeMDoc = document.getElementById('badgeMDoc');
      const badgeMTel = document.getElementById('badgeMTel');

      if (mayordomoSelect) {
        mayordomoSelect.addEventListener('change', function() {
          const opt = mayordomoSelect.options[mayordomoSelect.selectedIndex];
          if (opt && opt.dataset.doc) {
            badgeMDoc.textContent = opt.dataset.doc;
            badgeMTel.textContent = opt.dataset.tel;
            mayordomoBadge.style.display = 'block';
          } else {
            mayordomoBadge.style.display = 'none';
          }
        });
      }
    });
  </script>
  @endpush

</x-admin-layout>
