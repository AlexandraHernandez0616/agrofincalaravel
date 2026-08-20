<x-admin-layout title="Reportes del Sistema">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Reportes del Sistema</h1>
    <p>Genera y exporta reportes operativos y administrativos</p>
  </x-slot>

  <!-- ======================================================================
       TARJETA SUPERIOR: GENERAR REPORTE
       ====================================================================== -->
  <div class="reportes-filter-card">
    <div class="reportes-filter-title">Generar Reporte</div>

    <form action="{{ route('reportes.index') }}" method="GET" id="generateReportForm">
      <div class="reportes-form-grid">
        
        <!-- 1. Tipo de Reporte -->
        <div class="af-form-group">
          <label for="tipo_reporte">Tipo de Reporte</label>
          <select id="tipo_reporte" name="tipo_reporte" class="af-form-input">
            <option value="asistencia" {{ ($tipoReporte ?? 'asistencia') === 'asistencia' ? 'selected' : '' }}>Asistencia</option>
            <option value="liquidaciones" {{ ($tipoReporte ?? '') === 'liquidaciones' ? 'selected' : '' }}>Liquidaciones</option>
            <option value="pagos" {{ ($tipoReporte ?? '') === 'pagos' ? 'selected' : '' }}>Pagos</option>
            <option value="inventario" {{ ($tipoReporte ?? '') === 'inventario' ? 'selected' : '' }}>Inventario</option>
            <option value="produccion" {{ ($tipoReporte ?? '') === 'produccion' ? 'selected' : '' }}>Producción / Cosecha</option>
          </select>
        </div>

        <!-- 2. Trabajador -->
        <div class="af-form-group">
          <label for="id_trabajador">Trabajador</label>
          <select id="id_trabajador" name="id_trabajador" class="af-form-input">
            <option value="todos" {{ empty($idTrabajador) || $idTrabajador === 'todos' ? 'selected' : '' }}>Todos</option>
            @foreach ($trabajadores as $t)
              <option value="{{ $t->id_trabajador }}" {{ ($idTrabajador ?? '') == $t->id_trabajador ? 'selected' : '' }}>
                {{ $t->usuario?->name ?? 'Trabajador' }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- 3. Fecha Inicio -->
        <div class="af-form-group">
          <label for="fecha_inicio">Fecha Inicio</label>
          <input 
            type="date" 
            id="fecha_inicio" 
            name="fecha_inicio" 
            class="af-form-input" 
            value="{{ $fechaInicio ?? '' }}" 
            placeholder="dd/mm/aaaa"
          />
        </div>

        <!-- 4. Fecha Fin -->
        <div class="af-form-group">
          <label for="fecha_fin">Fecha Fin</label>
          <input 
            type="date" 
            id="fecha_fin" 
            name="fecha_fin" 
            class="af-form-input" 
            value="{{ $fechaFin ?? '' }}" 
            placeholder="dd/mm/aaaa"
          />
        </div>

      </div>

      <!-- Botón Generar Reporte -->
      <button type="submit" class="btn-primary" style="background: #16a34a;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
        <span>📑 Generar Reporte</span>
      </button>
    </form>
  </div>

  <!-- ======================================================================
       SECCIÓN INFERIOR: RESULTADOS DEL REPORTE
       ====================================================================== -->
  <div class="af-table-card" style="padding-top: 18px;">
    
    <!-- Barra Superior de Resultados y Botones de Exportación -->
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 24px 18px 24px; border-bottom: 1px solid var(--border);">
      <div style="display: flex; align-items: center; gap: 12px;">
        <h3 style="font-size: 16.5px; font-weight: 700; color: var(--secondary-color); margin: 0;">Resultados del Reporte</h3>
        <span style="font-size: 12.5px; color: var(--text-muted); font-weight: 600;">
          {{ count($resultados) }} registros
        </span>
      </div>

      <!-- Botones de Exportar -->
      <div style="display: flex; align-items: center; gap: 10px;">
        <!-- Botón PDF / Imprimir -->
        <button type="button" class="btn-export-pdf" onclick="window.print();">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
          <span>↓ PDF</span>
        </button>

        <!-- Botón Excel / CSV -->
        <a href="{{ route('reportes.export.excel', request()->query()) }}" class="btn-export-excel">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
          <span>↓ Excel</span>
        </a>
      </div>
    </div>

    <!-- ======================================================================
         TABLA DINÁMICA DE RESULTADOS (SEGÚN TIPO DE REPORTE)
         ====================================================================== -->
    <div class="af-table-responsive">
      <table class="af-table-data">
        
        @if ($tipoReporte === 'asistencia')
          <!-- CASO 1: REPORTE DE ASISTENCIA (IDÉNTICO A LA CAPTURA) -->
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Trabajador</th>
              <th>Entrada</th>
              <th>Salida</th>
              <th>Horas</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($resultados as $row)
              <tr>
                <td style="font-weight: 500; color: #1e40af;">{{ $row->formatted_fecha }}</td>
                <td style="font-weight: 500; color: var(--secondary-color);">{{ $row->trabajador_nombre }}</td>
                <td style="color: #334155;">{{ $row->formatted_hora_entrada }}</td>
                <td style="color: #334155;">{{ $row->formatted_hora_salida }}</td>
                <td style="font-weight: 600; color: #475569;">{{ $row->total_horas }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="table-empty-state">
                  <p style="color: var(--text-muted); margin: 0;">No se encontraron registros de asistencia para los filtros seleccionados.</p>
                </td>
              </tr>
            @endforelse
          </tbody>

        @elseif ($tipoReporte === 'liquidaciones')
          <!-- CASO 2: REPORTE DE LIQUIDACIONES -->
          <thead>
            <tr>
              <th>Código</th>
              <th>Trabajador</th>
              <th>Tarifa Aplicada</th>
              <th>Período</th>
              <th>Monto COP</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($resultados as $row)
              <tr>
                <td style="font-weight: 600; color: #1e40af;">LIQ-{{ str_pad($row->id_liquidacion, 3, '0', STR_PAD_LEFT) }}</td>
                <td style="font-weight: 500; color: var(--secondary-color);">{{ $row->trabajador_nombre }}</td>
                <td>{{ $row->tipo_tarifa_nombre }}</td>
                <td>{{ $row->formatted_periodo }}</td>
                <td style="font-weight: 700; color: #0f172a;">{{ $row->formatted_valor }}</td>
                <td>
                  <span class="pill-status {{ $row->estado === 'LIQUIDADA' ? 'pill-status-active' : 'pill-status-blue' }}">
                    {{ $row->estado }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="table-empty-state">
                  <p style="color: var(--text-muted); margin: 0;">No se encontraron liquidaciones para los filtros seleccionados.</p>
                </td>
              </tr>
            @endforelse
          </tbody>

        @elseif ($tipoReporte === 'pagos')
          <!-- CASO 3: REPORTE DE PAGOS -->
          <thead>
            <tr>
              <th>ID</th>
              <th>Trabajador</th>
              <th>Liquidación</th>
              <th>Fecha Pago</th>
              <th>Monto COP</th>
              <th>Método</th>
              <th>Referencia</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($resultados as $row)
              <tr>
                <td style="font-weight: 600; color: var(--text-muted);">{{ $row->id_pago }}</td>
                <td style="font-weight: 500; color: var(--secondary-color);">{{ $row->trabajador_nombre }}</td>
                <td>{{ $row->liquidacion_codigo }}</td>
                <td>{{ $row->formatted_fecha_pago }}</td>
                <td style="font-weight: 700; color: #0f172a;">{{ $row->formatted_monto }}</td>
                <td>
                  <span class="pill-status pill-status-active">{{ $row->metodo_pago }}</span>
                </td>
                <td>{{ $row->referencia_pago ?? '—' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="table-empty-state">
                  <p style="color: var(--text-muted); margin: 0;">No se encontraron pagos registrados para los filtros seleccionados.</p>
                </td>
              </tr>
            @endforelse
          </tbody>

        @elseif ($tipoReporte === 'inventario')
          <!-- CASO 4: REPORTE DE INVENTARIO -->
          <thead>
            <tr>
              <th>Código</th>
              <th>Nombre / Insumo</th>
              <th>Categoría</th>
              <th>Stock / Cantidad</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($resultados as $row)
              <tr>
                <td style="font-weight: 600; color: #1e40af;">{{ $row->codigo }}</td>
                <td style="font-weight: 500; color: var(--secondary-color);">{{ $row->nombre }}</td>
                <td>{{ $row->tipo }}</td>
                <td style="font-weight: 700; color: #0f172a;">{{ $row->cantidad }}</td>
                <td>
                  <span class="pill-status {{ $row->estado === 'DISPONIBLE' ? 'pill-status-active' : 'pill-status-amber' }}">
                    {{ $row->estado }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="table-empty-state">
                  <p style="color: var(--text-muted); margin: 0;">No hay elementos de inventario registrados.</p>
                </td>
              </tr>
            @endforelse
          </tbody>

        @elseif ($tipoReporte === 'produccion')
          <!-- CASO 5: REPORTE DE PRODUCCIÓN -->
          <thead>
            <tr>
              <th>Fecha Registro</th>
              <th>Lote</th>
              <th>Cultivo</th>
              <th>Cantidad Recolectada (Kg)</th>
              <th>Registrado Por</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($resultados as $row)
              <tr>
                <td>{{ $row->fecha_registro }}</td>
                <td style="font-weight: 500; color: var(--secondary-color);">{{ $row->lote?->nombre_lote ?? 'Lote' }}</td>
                <td>
                  <span class="pill-crop">{{ $row->cultivo?->nombre_cultivo ?? 'General' }}</span>
                </td>
                <td style="font-weight: 700; color: #166534;">{{ number_format($row->cantidad_recolectada, 2) }} kg</td>
                <td style="color: var(--text-muted);">{{ $row->usuario?->name ?? 'Admin' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="table-empty-state">
                  <p style="color: var(--text-muted); margin: 0;">No se encontraron registros de producción para los filtros seleccionados.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        @endif

      </table>
    </div>
  </div>

</x-admin-layout>
