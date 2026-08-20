<x-mayordomo-layout title="Solicitudes de Registro - AgroFinca">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Solicitudes de Registro</h1>
    <p>Aprueba o rechaza las solicitudes de nuevos trabajadores registradas en el sistema</p>
  </x-slot>

  <x-slot name="actions">
    @if ($solicitudes->count() > 0)
      <span style="background: #fef9c3; color: #854d0e; border: 1px solid #fde047; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700;">
        {{ $solicitudes->count() }} pendiente{{ $solicitudes->count() > 1 ? 's' : '' }}
      </span>
    @endif
  </x-slot>

  <!-- Notificaciones Flash -->
  @if (session('success'))
    <div class="af-alert success" style="margin-bottom: 20px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if (session('error'))
    <div class="af-alert error" style="margin-bottom: 20px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  <!-- ======================================================================
       TABLA DE SOLICITUDES DE REGISTRO
       ====================================================================== -->
  <div class="af-table-card">
    <div class="af-table-responsive">
      <table class="af-table-data" id="tablaSolicitudes">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Documento</th>
            <th>Usuario</th>
            <th>EPS</th>
            <th>RH</th>
            <th>Fecha Solicitud</th>
            <th style="text-align: center;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($solicitudes as $s)
            <tr id="fila-solicitud-{{ $s->id_solicitud }}">
              <td>
                <strong style="color: var(--secondary-color);">{{ $s->nombres }}</strong> {{ $s->apellidos }}
              </td>
              <td>{{ $s->documento }}</td>
              <td>
                <span style="font-weight: 600; color: #0284c7;">{{ '@' . $s->username }}</span>
              </td>
              <td>{{ $s->eps ?? '—' }}</td>
              <td>
                <span class="badge" style="font-size: 11.5px; padding: 2px 8px;">{{ $s->rh ?? '—' }}</span>
              </td>
              <td style="color: var(--text-muted);">{{ substr($s->fecha_solicitud, 0, 10) }}</td>
              <td style="text-align: center;">
                <div style="display: inline-flex; gap: 8px; justify-content: center;">
                  <!-- Botón Aprobar -->
                  <button type="button"
                    class="btn-primary"
                    style="padding: 6px 12px; font-size: 12.5px; border-radius: 8px; cursor: pointer;"
                    onclick="abrirModalAprobar({{ $s->id_solicitud }}, '{{ addslashes($s->nombres . ' ' . $s->apellidos) }}')">
                    ✓ Aprobar
                  </button>

                  <!-- Botón Rechazar -->
                  <button type="button"
                    class="btn-danger-action"
                    style="padding: 6px 12px; font-size: 12.5px; border-radius: 8px; cursor: pointer;"
                    onclick="abrirModalRechazar({{ $s->id_solicitud }}, '{{ addslashes($s->nombres . ' ' . $s->apellidos) }}')">
                    ✕ Rechazar
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="table-empty-state" style="padding: 36px; text-align: center; color: #166534; font-size: 15px; font-weight: 600;">
                ✅ No hay solicitudes pendientes
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- ======================================================================
       MODAL: APROBAR SOLICITUD
       ====================================================================== -->
  <div class="af-modal-overlay" id="modalAprobarSolicitud" style="display: none;">
    <div class="af-modal-box" style="max-width: 480px;">
      <div class="af-modal-header">
        <h3 class="af-modal-title" style="display: flex; align-items: center; gap: 8px;">
          <span style="color: #10b981;">✓</span> Aprobar Solicitud
        </h3>
        <button type="button" class="btn-modal-close" onclick="cerrarModal('modalAprobarSolicitud')">&times;</button>
      </div>

      <form id="formAprobarSolicitud" method="POST" action="">
        @csrf
        @method('PATCH')

        <div class="af-modal-body">
          <p style="font-size: 15px; color: var(--secondary-color); margin-bottom: 8px;" id="textoConfirmarAprobar">
            ¿Confirmas la aprobación de la solicitud?
          </p>
          <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">
            Al aprobar la solicitud, se creará automáticamente la cuenta de usuario para el trabajador y podrá iniciar sesión en el sistema.
          </p>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-secondary-action" onclick="cerrarModal('modalAprobarSolicitud')">Cancelar</button>
          <button type="submit" class="btn-primary">✓ Confirmar Aprobación</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ======================================================================
       MODAL: RECHAZAR SOLICITUD
       ====================================================================== -->
  <div class="af-modal-overlay" id="modalRechazarSolicitud" style="display: none;">
    <div class="af-modal-box" style="max-width: 480px;">
      <div class="af-modal-header">
        <h3 class="af-modal-title" style="display: flex; align-items: center; gap: 8px;">
          <span style="color: #ef4444;">✕</span> Rechazar Solicitud
        </h3>
        <button type="button" class="btn-modal-close" onclick="cerrarModal('modalRechazarSolicitud')">&times;</button>
      </div>

      <form id="formRechazarSolicitud" method="POST" action="">
        @csrf
        @method('PATCH')

        <div class="af-modal-body">
          <p style="font-size: 15px; color: var(--secondary-color); margin-bottom: 12px;" id="textoConfirmarRechazar">
            ¿Rechazar la solicitud de registro?
          </p>

          <div class="form-group">
            <label for="observacionRechazo" class="af-form-label">
              Motivo del rechazo <span style="color: var(--text-muted); font-weight: 400;">(opcional)</span>
            </label>
            <textarea
              name="observacion"
              id="observacionRechazo"
              rows="3"
              class="af-form-input"
              placeholder="Ej: Documento no legible, datos duplicados, etc."></textarea>
          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-secondary-action" onclick="cerrarModal('modalRechazarSolicitud')">Cancelar</button>
          <button type="submit" class="btn-danger-action">✕ Confirmar Rechazo</button>
        </div>
      </form>
    </div>
  </div>

  @push('scripts')
    <script>
      function abrirModalAprobar(id, nombre) {
        const form = document.getElementById('formAprobarSolicitud');
        form.action = "{{ url('/mayordomo/solicitudes') }}/" + id + "/aprobar";
        document.getElementById('textoConfirmarAprobar').innerHTML = '¿Confirmas la aprobación de la solicitud de <strong>' + nombre + '</strong>?';
        
        const modal = document.getElementById('modalAprobarSolicitud');
        modal.style.display = 'flex';
      }

      function abrirModalRechazar(id, nombre) {
        const form = document.getElementById('formRechazarSolicitud');
        form.action = "{{ url('/mayordomo/solicitudes') }}/" + id + "/rechazar";
        document.getElementById('textoConfirmarRechazar').innerHTML = '¿Confirmas el rechazo de la solicitud de <strong>' + nombre + '</strong>?';
        document.getElementById('observacionRechazo').value = '';

        const modal = document.getElementById('modalRechazarSolicitud');
        modal.style.display = 'flex';
      }

      function cerrarModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.style.display = 'none';
        }
      }

      // Cerrar modal al hacer clic en el backdrop
      document.querySelectorAll('.af-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
          if (e.target === this) {
            this.style.display = 'none';
          }
        });
      });
    </script>
  @endpush

</x-mayordomo-layout>
