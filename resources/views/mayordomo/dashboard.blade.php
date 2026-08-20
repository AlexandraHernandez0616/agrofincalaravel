<x-mayordomo-layout title="Panel Operativo del Mayordomo">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Panel Operativo del Mayordomo</h1>
    <p>Bienvenido de nuevo, <strong>{{ Auth::user()->name }}</strong>. Monitorea y gestiona las operaciones en campo.</p>
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
       TARJETAS KPI DEL MAYORDOMO
       ====================================================================== -->
  <div class="bitacora-kpi-grid">
    <!-- 1. Trabajadores Activos -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Trabajadores Activos</div>
      <div class="lotes-kpi-number">{{ $trabajadoresActivos }}</div>
    </div>

    <!-- 2. Asistencias de Hoy -->
    <div class="lotes-kpi-card blue">
      <div class="lotes-kpi-label">Asistencias Hoy</div>
      <div class="lotes-kpi-number">{{ $asistenciasHoy }}</div>
    </div>

    <!-- 3. Lotes en Producción -->
    <div class="lotes-kpi-card yellow">
      <div class="lotes-kpi-label">Lotes en Finca</div>
      <div class="lotes-kpi-number">{{ $lotesActivos }}</div>
    </div>

    <!-- 4. Permiso Delegado -->
    <div class="lotes-kpi-card {{ $permisoActivo ? 'green' : 'red' }}">
      <div class="lotes-kpi-label">Liquidación Delegada</div>
      <div style="font-size: 18px; font-weight: 800; margin-top: 4px; color: {{ $permisoActivo ? '#166534' : '#dc2626' }};">
        @if ($permisoActivo)
          🟢 ACTIVO (Hasta {{ $permisoActivo->formatted_fecha_fin }})
        @else
          ⚪ Sin Permiso Activo
        @endif
      </div>
    </div>
  </div>

  <!-- ======================================================================
       ACCIONES RÁPIDAS DE CAMPO
       ====================================================================== -->
  <div class="af-table-card" style="padding: 20px 24px; margin-bottom: 24px;">
    <h3 style="font-size: 16px; font-weight: 700; color: var(--secondary-color); margin-bottom: 14px;">
      ⚡ Accesos Rápidos de Operación
    </h3>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
      <a href="{{ url('/mayordomo/asistencias') }}" class="btn-primary" style="text-decoration: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span>Control de Asistencias</span>
      </a>

      <a href="{{ url('/mayordomo/produccion') }}" class="btn-secondary-action" style="text-decoration: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        <span>Registro de Cosechas</span>
      </a>

      <a href="{{ url('/mayordomo/inventario') }}" class="btn-secondary-action" style="text-decoration: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/></svg>
        <span>Herramientas e Insumos</span>
      </a>

      <a href="{{ url('/mayordomo/liquidaciones') }}" class="btn-secondary-action" style="text-decoration: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        <span>Liquidaciones Delegadas</span>
      </a>
    </div>
  </div>

  <!-- ======================================================================
       GRILLA DE MONITOREO EN VIVO: ASISTENCIAS Y COSECHAS
       ====================================================================== -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    
    <!-- 1. Últimas Asistencias -->
    <div class="af-table-card" style="padding-top: 16px;">
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 20px 12px 20px; border-bottom: 1px solid var(--border);">
        <h3 style="font-size: 15px; font-weight: 700; color: var(--secondary-color); margin: 0;">⏱️ Asistencias Recientes</h3>
        <a href="{{ url('/mayordomo/asistencias') }}" style="font-size: 12.5px; color: #10b981; font-weight: 600; text-decoration: none;">Ver todas &rarr;</a>
      </div>
      <div class="af-table-responsive">
        <table class="af-table-data" style="font-size: 13px;">
          <thead>
            <tr>
              <th>Trabajador</th>
              <th>Fecha</th>
              <th>Entrada</th>
              <th>Salida</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($asistenciasRecientes as $asist)
              <tr>
                <td style="font-weight: 600; color: var(--secondary-color);">{{ $asist->trabajador_nombre }}</td>
                <td>{{ $asist->formatted_fecha }}</td>
                <td style="color: #15803d; font-weight: 600;">{{ $asist->formatted_hora_entrada }}</td>
                <td>{{ $asist->formatted_hora_salida }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="table-empty-state" style="padding: 20px;">
                  No hay asistencias registradas hoy.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- 2. Últimas Cosechas / Producción -->
    <div class="af-table-card" style="padding-top: 16px;">
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 20px 12px 20px; border-bottom: 1px solid var(--border);">
        <h3 style="font-size: 15px; font-weight: 700; color: var(--secondary-color); margin: 0;">🌿 Cosechas Recientes</h3>
        <a href="{{ url('/mayordomo/produccion') }}" style="font-size: 12.5px; color: #10b981; font-weight: 600; text-decoration: none;">Ver todas &rarr;</a>
      </div>
      <div class="af-table-responsive">
        <table class="af-table-data" style="font-size: 13px;">
          <thead>
            <tr>
              <th>Lote</th>
              <th>Cultivo</th>
              <th>Kilos</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($cosechasRecientes as $cos)
              <tr>
                <td style="font-weight: 600; color: var(--secondary-color);">{{ $cos->lote?->nombre ?? 'Lote' }}</td>
                <td>
                  <span class="pill-crop">{{ $cos->lote?->cultivo?->nombre ?? 'Cosecha' }}</span>
                </td>
                <td style="font-weight: 700; color: #166534;">{{ number_format((float)$cos->cantidad, 2) }} {{ $cos->unidad_medida ?? 'kg' }}</td>
                <td style="color: var(--text-muted);">{{ $cos->formatted_fecha }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="table-empty-state" style="padding: 20px;">
                  No hay registros de cosecha recientes.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

</x-mayordomo-layout>
