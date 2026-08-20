<x-trabajador-layout title="Portal del Colaborador">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Portal del Colaborador</h1>
    <p>Bienvenido de nuevo, <strong>{{ Auth::user()->name }}</strong>. Aquí puedes consultar tu historial laboral y asistencias.</p>
  </x-slot>

  <!-- ======================================================================
       TARJETAS KPI DEL TRABAJADOR
       ====================================================================== -->
  <div class="bitacora-kpi-grid">
    <!-- 1. Estado -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Estado Laboral</div>
      <div style="font-size: 20px; font-weight: 800; color: #166534; margin-top: 4px;">
        🟢 {{ $trabajador?->estado_trabajador ?? 'ACTIVO' }}
      </div>
    </div>

    <!-- 2. EPS y RH -->
    <div class="lotes-kpi-card blue">
      <div class="lotes-kpi-label">EPS y Tipo de Sangre</div>
      <div style="font-size: 16px; font-weight: 700; color: #1e40af; margin-top: 4px;">
        {{ strtoupper($trabajador?->eps ?? 'No reg.') }} ({{ strtoupper($trabajador?->rh ?? 'N/A') }})
      </div>
    </div>

    <!-- 3. Asistencias -->
    <div class="lotes-kpi-card yellow">
      <div class="lotes-kpi-label">Asistencias Totales</div>
      <div class="lotes-kpi-number">{{ $asistenciasCount }}</div>
    </div>

    <!-- 4. Liquidaciones -->
    <div class="lotes-kpi-card green">
      <div class="lotes-kpi-label">Liquidaciones Generadas</div>
      <div class="lotes-kpi-number">{{ $liquidacionesCount }}</div>
    </div>
  </div>

  <!-- ======================================================================
       TABLAS: ASISTENCIAS Y LIQUIDACIONES
       ====================================================================== -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    
    <!-- 1. Mis Asistencias Recientes -->
    <div class="af-table-card" style="padding-top: 16px;">
      <div style="padding: 0 20px 12px 20px; border-bottom: 1px solid var(--border);">
        <h3 style="font-size: 15px; font-weight: 700; color: var(--secondary-color); margin: 0;">⏱️ Mis Asistencias Recientes</h3>
      </div>
      <div class="af-table-responsive">
        <table class="af-table-data" style="font-size: 13px;">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Entrada</th>
              <th>Salida</th>
              <th>Horas</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($asistenciasRecientes as $asist)
              <tr>
                <td style="font-weight: 600; color: #1e40af;">{{ $asist->formatted_fecha }}</td>
                <td style="color: #15803d; font-weight: 600;">{{ $asist->formatted_hora_entrada }}</td>
                <td style="color: #334155;">{{ $asist->formatted_hora_salida }}</td>
                <td style="font-weight: 600; color: #475569;">{{ $asist->total_horas }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="table-empty-state" style="padding: 20px;">
                  No tienes asistencias registradas aún.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- 2. Mis Liquidaciones -->
    <div class="af-table-card" style="padding-top: 16px;">
      <div style="padding: 0 20px 12px 20px; border-bottom: 1px solid var(--border);">
        <h3 style="font-size: 15px; font-weight: 700; color: var(--secondary-color); margin: 0;">💵 Mis Liquidaciones</h3>
      </div>
      <div class="af-table-responsive">
        <table class="af-table-data" style="font-size: 13px;">
          <thead>
            <tr>
              <th>Código</th>
              <th>Tarifa</th>
              <th>Monto</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($liquidacionesRecientes as $lq)
              <tr>
                <td style="font-weight: 600; color: #1e40af;">LIQ-{{ str_pad($lq->id_liquidacion, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $lq->tipo_tarifa_nombre }}</td>
                <td style="font-weight: 700; color: #166534;">{{ $lq->formatted_valor }}</td>
                <td>
                  <span class="pill-status {{ $lq->estado === 'LIQUIDADA' ? 'pill-status-active' : 'pill-status-blue' }}">
                    {{ $lq->estado }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="table-empty-state" style="padding: 20px;">
                  No tienes liquidaciones registradas aún.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

</x-trabajador-layout>
