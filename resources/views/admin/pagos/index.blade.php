<x-admin-layout title="Gestión de Pagos">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Gestión de Pagos</h1>
    <p>Registra y administra los pagos a trabajadores</p>
  </x-slot>

  <!-- Botón de Acción Superior -->
  <x-slot name="actions">
    <button type="button" class="btn-primary" data-af-modal-open="createPagoModal">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
      <span>+ Registrar Pago</span>
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
       TARJETAS KPI SUPERIORES (TOTAL PAGOS, MONTO TOTAL, TRANSFERENCIA, EFECTIVO)
       ====================================================================== -->
  <div class="pagos-kpi-grid">
    <!-- 1. Total Pagos -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Total Pagos</div>
      <div class="lotes-kpi-number">{{ $totalPagos }}</div>
    </div>

    <!-- 2. Monto Total COP -->
    <div class="lotes-kpi-card blue">
      <div class="lotes-kpi-label">Monto Total COP</div>
      <div class="lotes-kpi-number">{{ $montoTotalFormatted }}</div>
    </div>

    <!-- 3. Por Transferencia -->
    <div class="lotes-kpi-card yellow">
      <div class="lotes-kpi-label">Por Transferencia</div>
      <div class="lotes-kpi-number">{{ $porTransferencia }}</div>
    </div>

    <!-- 4. Por Efectivo -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Por Efectivo</div>
      <div class="lotes-kpi-number">{{ $porEfectivo }}</div>
    </div>
  </div>

  <!-- ======================================================================
       BARRA DE BÚSQUEDA Y FILTRO POR MÉTODO DE PAGO
       ====================================================================== -->
  <div class="table-toolbar-row">
    <div class="table-search-bar-wrap" style="flex: 1;">
      <span class="search-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
      </span>
      <input 
        type="text" 
        id="pagoLiveSearch" 
        class="table-search-bar-input" 
        placeholder="Buscar por trabajador o documento..." 
        value="{{ $search ?? '' }}" 
        autocomplete="off"
      />
    </div>

    <!-- Filtro de Método de Pago -->
    <div class="table-filter-select-wrap">
      <select id="pagoMethodFilter" class="table-filter-select">
        <option value="todos" {{ empty($metodo) || strtolower($metodo) === 'todos' ? 'selected' : '' }}>Todos los métodos</option>
        @foreach ($metodosDisponibles as $m)
          <option value="{{ strtolower($m) }}" {{ strtolower($metodo ?? '') === strtolower($m) ? 'selected' : '' }}>
            {{ $m }}
          </option>
        @endforeach
      </select>
    </div>
  </div>

  <!-- ======================================================================
       TABLA PRINCIPAL DE PAGOS
       ====================================================================== -->
  <div class="af-table-card">
    <div class="af-table-responsive">
      <table class="af-table-data" id="pagosTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Trabajador</th>
            <th>Liquidación</th>
            <th>Fecha Pago</th>
            <th>Monto</th>
            <th>Método</th>
            <th>Referencia</th>
            <th style="text-align: right; padding-right: 24px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($pagos as $pago)
            <tr 
              data-method="{{ strtolower($pago->metodo_pago ?? '') }}" 
              data-doc="{{ $pago->trabajador_documento }}"
              data-ref="{{ strtolower($pago->referencia_pago ?? '') }}"
            >
              <td style="font-weight: 600; color: var(--text-muted);">{{ $pago->id_pago }}</td>
              <td style="font-weight: 500; color: var(--secondary-color);">{{ $pago->trabajador_nombre }}</td>
              <td>
                <span style="font-weight: 600; color: #475569; background: #f1f5f9; padding: 3px 8px; border-radius: 6px; font-size: 12px;">
                  {{ $pago->liquidacion_codigo }}
                </span>
              </td>
              <td>{{ $pago->formatted_fecha_pago }}</td>
              <td style="font-weight: 700; color: #0f172a;">{{ $pago->formatted_monto }}</td>
              <td>
                @if (stripos($pago->metodo_pago, 'efectivo') !== false)
                  <span class="pill-status pill-status-active">Efectivo</span>
                @elseif (stripos($pago->metodo_pago, 'transferencia') !== false)
                  <span class="pill-status pill-status-blue">Transferencia</span>
                @else
                  <span class="pill-status pill-status-amber">{{ $pago->metodo_pago }}</span>
                @endif
              </td>
              <td>{{ $pago->referencia_pago ?? '—' }}</td>
              <td style="text-align: right; padding-right: 24px;">
                <div class="table-actions-cell" style="justify-content: flex-end; align-items: center; gap: 8px;">
                  <!-- Botón Ver Comprobante (Icono Ojo) -->
                  <button 
                    type="button" 
                    class="action-icon-btn btn-view" 
                    title="Ver comprobante de pago" 
                    data-af-modal-open="viewPagoModal_{{ $pago->id_pago }}"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>

                  <!-- Botón Eliminar Pago (Icono Papelera) -->
                  <form action="{{ route('pagos.destroy', $pago->id_pago) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este registro de pago?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-icon-btn btn-delete" title="Eliminar registro de pago">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="table-empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                <p style="margin-top: 8px; color: var(--text-muted);">No hay registros de pago en este criterio.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- ======================================================================
       MODAL 1: REGISTRAR PAGO A TRABAJADOR
       ====================================================================== -->
  <div class="af-modal-overlay" id="createPagoModal">
    <div class="af-modal-card" style="max-width: 620px;">
      <div class="af-modal-header">
        <div class="af-modal-title-wrap">
          <div class="af-modal-icon-badge green">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <div>
            <div class="af-modal-title">Registrar Pago a Trabajador</div>
            <div class="af-modal-subtitle">Asigna el desembolso a una liquidación aprobada o generada</div>
          </div>
        </div>
        <button type="button" class="af-modal-close-btn" data-af-modal-close="createPagoModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
        </button>
      </div>

      <form action="{{ route('pagos.store') }}" method="POST" id="newPagoForm">
        @csrf
        <div class="af-modal-body">
          <div class="af-form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
            
            <!-- 1. Selección de Liquidación -->
            <div class="af-form-group" style="grid-column: span 2;">
              <label for="new_id_liquidacion">Liquidación Pendiente de Pago <span class="req">*</span></label>
              <select id="new_id_liquidacion" name="id_liquidacion" class="af-form-input" required>
                <option value="" disabled selected>Selecciona una liquidación</option>
                @foreach ($liquidacionesDisponibles as $liq)
                  <option 
                    value="{{ $liq->id_liquidacion }}"
                    data-worker="{{ $liq->trabajador_nombre }}"
                    data-doc="{{ $liq->trabajador?->usuario?->documento ?? '-' }}"
                    data-monto="{{ $liq->valor_calculado }}"
                    data-estado="{{ $liq->estado }}"
                    data-tarifa="{{ $liq->tipo_tarifa_nombre }}"
                  >
                    LIQ-{{ str_pad($liq->id_liquidacion, 3, '0', STR_PAD_LEFT) }} — {{ $liq->trabajador_nombre }} ({{ $liq->formatted_valor }} COP • {{ $liq->estado }})
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Badge Informativo de Liquidación -->
            <div id="pagoLiqBadge" class="worker-preview-badge" style="grid-column: span 2; display: none;">
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                  <strong>Beneficiario:</strong> <span id="badgeWorker">-</span> (Doc: <span id="badgeWorkerDoc">-</span>)
                </div>
                <div>
                  <span class="pill-status pill-status-blue" style="font-size: 11px;">
                    Tarifa: <span id="badgeTarifa">-</span>
                  </span>
                </div>
              </div>
            </div>

            <!-- 2. Fecha de Pago -->
            <div class="af-form-group">
              <label for="new_fecha_pago">Fecha del Desembolso <span class="req">*</span></label>
              <input 
                type="date" 
                id="new_fecha_pago" 
                name="fecha_pago" 
                class="af-form-input" 
                value="{{ date('Y-m-d') }}" 
                required 
              />
            </div>

            <!-- 3. Monto a Pagar -->
            <div class="af-form-group">
              <label for="new_monto">Monto en Pesos (COP) <span class="req">*</span></label>
              <input 
                type="number" 
                step="0.01" 
                id="new_monto" 
                name="monto" 
                class="af-form-input" 
                placeholder="0.00" 
                required 
              />
            </div>

            <!-- 4. Método de Pago -->
            <div class="af-form-group">
              <label for="new_metodo_pago">Método de Pago <span class="req">*</span></label>
              <select id="new_metodo_pago" name="metodo_pago" class="af-form-input" required>
                <option value="Efectivo" selected>Efectivo</option>
                <option value="Transferencia">Transferencia Bancaria</option>
                <option value="Nequi / Daviplata">Nequi / Daviplata</option>
                <option value="Cheque">Cheque</option>
              </select>
            </div>

            <!-- 5. Referencia de Pago -->
            <div class="af-form-group">
              <label for="new_referencia_pago">Referencia / Comprobante (Opcional)</label>
              <input 
                type="text" 
                id="new_referencia_pago" 
                name="referencia_pago" 
                class="af-form-input" 
                placeholder="Ej: TRX-98745 o Recibo #12" 
              />
            </div>

            <!-- 6. Observaciones -->
            <div class="af-form-group" style="grid-column: span 2;">
              <label for="new_pago_observacion">Observaciones / Notas</label>
              <textarea 
                id="new_pago_observacion" 
                name="observacion" 
                rows="2" 
                class="af-form-input" 
                placeholder="Detalles adicionales sobre la entrega del dinero..."
              ></textarea>
            </div>

          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-cancel" data-af-modal-close="createPagoModal">Cancelar</button>
          <button type="submit" class="btn-save">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <span>Confirmar Pago</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ======================================================================
       MODALES DINÁMICOS: VER COMPROBANTE DE PAGO
       ====================================================================== -->
  @foreach ($pagos as $pago)
    <div class="af-modal-overlay" id="viewPagoModal_{{ $pago->id_pago }}">
      <div class="af-modal-card" style="max-width: 560px;">
        <div class="af-modal-header">
          <div class="af-modal-title-wrap">
            <div class="af-modal-icon-badge green">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div>
              <div class="af-modal-title">Comprobante de Pago #{{ $pago->id_pago }}</div>
              <div class="af-modal-subtitle">Beneficiario: <strong>{{ $pago->trabajador_nombre }}</strong></div>
            </div>
          </div>
          <button type="button" class="af-modal-close-btn" data-af-modal-close="viewPagoModal_{{ $pago->id_pago }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
          </button>
        </div>

        <div class="af-modal-body">
          <!-- Detalle del Beneficiario y Liquidación -->
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 18px; margin-bottom: 16px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13.5px;">
              <div>
                <span style="color: var(--text-muted);">Trabajador:</span>
                <div style="font-weight: 600; color: #0f172a;">{{ $pago->trabajador_nombre }}</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Documento:</span>
                <div style="font-weight: 600; color: #0f172a;">{{ $pago->trabajador_documento }}</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Liquidación Asociada:</span>
                <div style="font-weight: 600; color: #1e40af;">{{ $pago->liquidacion_codigo }}</div>
              </div>
              <div>
                <span style="color: var(--text-muted);">Método Utilizado:</span>
                <div>
                  @if (stripos($pago->metodo_pago, 'efectivo') !== false)
                    <span class="pill-status pill-status-active">Efectivo</span>
                  @elseif (stripos($pago->metodo_pago, 'transferencia') !== false)
                    <span class="pill-status pill-status-blue">Transferencia</span>
                  @else
                    <span class="pill-status pill-status-amber">{{ $pago->metodo_pago }}</span>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <!-- Monto y Fechas -->
          <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px 18px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13.5px;">
              <span style="color: #166534;">Fecha de Pago:</span>
              <strong style="color: #14532d;">{{ $pago->formatted_fecha_pago }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13.5px;">
              <span style="color: #166534;">Referencia / Recibo:</span>
              <strong style="color: #14532d;">{{ $pago->referencia_pago ?? 'No especificada' }}</strong>
            </div>
            <div style="height: 1px; background: #bbf7d0; margin: 10px 0;"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 16px;">
              <span style="color: #166534; font-weight: 700;">Monto Pagado:</span>
              <span style="font-size: 22px; font-weight: 800; color: #15803d;">{{ $pago->formatted_monto }}</span>
            </div>
          </div>

          @if (!empty($pago->observacion))
            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">
              <strong>Observaciones:</strong>
              <p style="margin-top: 2px;">{{ $pago->observacion }}</p>
            </div>
          @endif

          <div style="font-size: 12px; color: var(--text-muted); border-top: 1px dashed var(--border); padding-top: 10px;">
            Registrado por: <strong>{{ $pago->registrador?->name ?? 'Administrador' }}</strong>
          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-cancel" data-af-modal-close="viewPagoModal_{{ $pago->id_pago }}">Cerrar</button>
        </div>
      </div>
    </div>
  @endforeach

  <!-- ======================================================================
       SCRIPTS: FILTROS EN VIVO Y AUTO-RELLENO EN MODAL
       ====================================================================== -->
  @push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // 1. Filtros en Vivo en Tabla de Pagos
      const liveSearch = document.getElementById('pagoLiveSearch');
      const methodFilter = document.getElementById('pagoMethodFilter');
      const tableRows = document.querySelectorAll('#pagosTable tbody tr');

      function filterPagos() {
        const query = (liveSearch ? liveSearch.value : '').toLowerCase().trim();
        const selectedMethod = (methodFilter ? methodFilter.value : 'todos').toLowerCase();

        tableRows.forEach(row => {
          if (row.querySelector('.table-empty-state')) return;

          const rowText = row.textContent.toLowerCase();
          const rowDoc = (row.getAttribute('data-doc') || '').toLowerCase();
          const rowRef = (row.getAttribute('data-ref') || '').toLowerCase();
          const rowMethod = (row.getAttribute('data-method') || '').toLowerCase();

          const matchesQuery = !query || rowText.includes(query) || rowDoc.includes(query) || rowRef.includes(query);
          const matchesMethod = (selectedMethod === 'todos') || rowMethod.includes(selectedMethod);

          if (matchesQuery && matchesMethod) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      }

      if (liveSearch) liveSearch.addEventListener('input', filterPagos);
      if (methodFilter) methodFilter.addEventListener('change', filterPagos);

      // 2. Auto-relleno de datos al seleccionar liquidación en Modal
      const liqSelect = document.getElementById('new_id_liquidacion');
      const montoInput = document.getElementById('new_monto');
      const liqBadge = document.getElementById('pagoLiqBadge');
      const badgeWorker = document.getElementById('badgeWorker');
      const badgeWorkerDoc = document.getElementById('badgeWorkerDoc');
      const badgeTarifa = document.getElementById('badgeTarifa');

      if (liqSelect) {
        liqSelect.addEventListener('change', function() {
          const opt = liqSelect.options[liqSelect.selectedIndex];
          if (opt && opt.dataset.worker) {
            if (montoInput && opt.dataset.monto) {
              montoInput.value = parseFloat(opt.dataset.monto).toFixed(0);
            }
            if (badgeWorker) badgeWorker.textContent = opt.dataset.worker;
            if (badgeWorkerDoc) badgeWorkerDoc.textContent = opt.dataset.doc;
            if (badgeTarifa) badgeTarifa.textContent = opt.dataset.tarifa || 'General';
            if (liqBadge) liqBadge.style.display = 'block';
          } else {
            if (liqBadge) liqBadge.style.display = 'none';
          }
        });
      }
    });
  </script>
  @endpush

</x-admin-layout>
