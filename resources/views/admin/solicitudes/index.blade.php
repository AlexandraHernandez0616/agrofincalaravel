<x-admin-layout title="Solicitudes de Registro">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Solicitudes de Registro</h1>
    <p>Revisa y aprueba el acceso de nuevos trabajadores a la finca</p>
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

  <!-- Filtro de Estado -->
  <div class="table-toolbar-row">
    <div style="font-size: 15px; font-weight: 600; color: var(--secondary-color);">
      Filtrar solicitudes por estado:
    </div>
    <div class="table-filter-select-wrap">
      <select onchange="window.location.href='{{ route('solicitudes.index') }}?estado=' + this.value" class="table-filter-select">
        <option value="PENDIENTE" {{ strtoupper($estado ?? '') === 'PENDIENTE' ? 'selected' : '' }}>Pendientes de Revisión</option>
        <option value="APROBADA" {{ strtoupper($estado ?? '') === 'APROBADA' ? 'selected' : '' }}>Aprobadas</option>
        <option value="RECHAZADA" {{ strtoupper($estado ?? '') === 'RECHAZADA' ? 'selected' : '' }}>Rechazadas</option>
        <option value="todos" {{ strtolower($estado ?? '') === 'todos' ? 'selected' : '' }}>Todas las solicitudes</option>
      </select>
    </div>
  </div>

  <!-- Tabla de Solicitudes -->
  <div class="af-table-card">
    <div class="af-table-responsive">
      <table class="af-table-data">
        <thead>
          <tr>
            <th>Nombre Completo</th>
            <th>Documento</th>
            <th>Teléfono</th>
            <th>EPS / RH</th>
            <th>Usuario</th>
            <th>Fecha Solicitud</th>
            <th>Estado</th>
            <th style="text-align: right; padding-right: 24px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($solicitudes as $solicitud)
            <tr>
              <td style="font-weight: 600; color: var(--secondary-color);">{{ $solicitud->name }}</td>
              <td>{{ $solicitud->documento }}</td>
              <td>{{ $solicitud->telefono }}</td>
              <td>{{ $solicitud->eps }} ({{ $solicitud->rh }})</td>
              <td><code>{{ $solicitud->username }}</code></td>
              <td>{{ $solicitud->formatted_fecha_solicitud ?? '-' }}</td>
              <td>
                @if ($solicitud->estado === 'PENDIENTE')
                  <span class="pill-status pill-status-amber">Pendiente</span>
                @elseif ($solicitud->estado === 'APROBADA')
                  <span class="pill-status pill-status-active">Aprobada</span>
                @else
                  <span class="pill-status pill-status-inactive">Rechazada</span>
                @endif
              </td>
              <td style="text-align: right; padding-right: 24px;">
                @if ($solicitud->estado === 'PENDIENTE')
                  <div style="display: inline-flex; gap: 8px; justify-content: flex-end;">
                    <!-- Aprobar -->
                    <form action="{{ route('solicitudes.aprobar', $solicitud->id_solicitud) }}" method="POST" onsubmit="return confirm('¿Deseas aprobar esta solicitud y crear el acceso como trabajador?');">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="pill-btn-toggle habilitar" title="Aprobar trabajador">
                        ✓ Aprobar
                      </button>
                    </form>

                    <!-- Rechazar -->
                    <form action="{{ route('solicitudes.rechazar', $solicitud->id_solicitud) }}" method="POST" onsubmit="return confirm('¿Deseas rechazar esta solicitud de acceso?');">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="pill-btn-toggle deshabilitar" title="Rechazar solicitud">
                        ✕ Rechazar
                      </button>
                    </form>
                  </div>
                @else
                  <span style="font-size: 12.5px; color: var(--text-muted);">
                    {{ $solicitud->estado === 'APROBADA' ? '✓ Gestionada' : '✕ Finalizada' }}
                  </span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="table-empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <p style="margin-top: 8px; color: var(--text-muted);">No hay solicitudes de registro en este estado.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    @if ($solicitudes->hasPages())
      <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
        {{ $solicitudes->links() }}
      </div>
    @endif
  </div>
</x-admin-layout>
