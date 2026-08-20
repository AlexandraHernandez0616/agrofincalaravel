<x-admin-layout title="Gestión de Trabajadores">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Gestión de Trabajadores</h1>
    <p>Consulta y administra los trabajadores de la finca</p>
  </x-slot>

  <!-- Mensajes de Notificación Flash -->
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

  <!-- Barra de Búsqueda y Selector de Filtro de Estado -->
  <div class="table-toolbar-row">
    <div class="table-search-bar-wrap">
      <span class="search-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
      </span>
      <input 
        type="text" 
        id="tableLiveSearch" 
        class="table-search-bar-input" 
        placeholder="Buscar por nombre, apellido o documento..." 
        value="{{ $search ?? '' }}" 
        autocomplete="off"
      />
    </div>

    <div class="table-filter-select-wrap">
      <select id="tableStateFilter" class="table-filter-select">
        <option value="todos" {{ empty($estado) || strtolower($estado) === 'todos' ? 'selected' : '' }}>Todos</option>
        <option value="ACTIVO" {{ strtoupper($estado ?? '') === 'ACTIVO' ? 'selected' : '' }}>Activo</option>
        <option value="INACTIVO" {{ strtoupper($estado ?? '') === 'INACTIVO' ? 'selected' : '' }}>Inactivo</option>
      </select>
    </div>
  </div>

  <!-- Tarjeta con Tabla de Datos (Estilo Idéntico al Screenshot) -->
  <div class="af-table-card">
    <div class="af-table-responsive">
      <table class="af-table-data">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Documento</th>
            <th>EPS</th>
            <th>RH</th>
            <th>Estado</th>
            <th>Fecha Registro</th>
            <th style="text-align: center;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($trabajadores as $trabajador)
            <tr data-status="{{ $trabajador->estado_trabajador }}">
              <td style="font-weight: 500;">{{ $trabajador->nombres }}</td>
              <td>{{ $trabajador->apellidos }}</td>
              <td>{{ $trabajador->documento }}</td>
              <td>{{ $trabajador->eps ?? '-' }}</td>
              <td>{{ $trabajador->rh ?? '-' }}</td>
              <td>
                @if ($trabajador->is_active)
                  <span class="pill-status pill-status-active">ACTIVO</span>
                @else
                  <span class="pill-status pill-status-inactive">Inactivo</span>
                @endif
              </td>
              <td>{{ $trabajador->fecha_registro_date }}</td>
              <td style="text-align: center;">
                <div class="table-actions-cell" style="justify-content: center;">
                  <!-- Botón Ver Detalles (Icono Ojo) -->
                  <button 
                    type="button" 
                    class="action-icon-btn btn-view" 
                    title="Ver detalles" 
                    data-af-modal-open="viewTrabajadorModal_{{ $trabajador->id_trabajador }}"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8">
                <div class="table-empty-state">
                  <div class="table-empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                  </div>
                  <h3 style="font-size: 16px; font-weight: 700; color: var(--secondary-color); margin-bottom: 6px;">No hay trabajadores registrados</h3>
                  <p style="font-size: 14px;">Los trabajadores deben solicitar su registro desde la pantalla de acceso y ser aprobados por el mayordomo.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if ($trabajadores->hasPages())
    <div style="margin-top: 20px;">
      {{ $trabajadores->links() }}
    </div>
  @endif

  <!-- ======================================================================
       MODALES DINÁMICOS POR TRABAJADOR (VER DETALLES Y EDITAR)
       ====================================================================== -->
  @foreach ($trabajadores as $trabajador)
    <!-- Modal: Ver Ficha de Detalles -->
    <div class="af-modal-overlay" id="viewTrabajadorModal_{{ $trabajador->id_trabajador }}">
      <div class="af-modal-card">
        <div class="af-modal-header">
          <div class="af-modal-title-wrap">
            <div class="af-modal-icon-badge">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <div>
              <div class="af-modal-title">Ficha del Trabajador</div>
              <div style="font-size: 12.5px; color: var(--text-muted);">Detalles registrados en AgroFinca</div>
            </div>
          </div>
          <button type="button" class="af-modal-close-btn" data-af-modal-close="viewTrabajadorModal_{{ $trabajador->id_trabajador }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
          </button>
        </div>

        <div class="af-modal-body">
          <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
            <div style="width: 58px; height: 58px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800;">
              {{ $trabajador->initials }}
            </div>
            <div>
              <h3 style="font-size: 18px; font-weight: 800; color: var(--secondary-color);">{{ $trabajador->name }}</h3>
              <div style="font-size: 13.5px; color: var(--text-muted); margin-top: 2px;">
                Doc: <strong>{{ $trabajador->documento }}</strong> &bull; 
                <span class="badge" style="font-size: 11px; padding: 2px 8px;">TRABAJADOR</span>
              </div>
            </div>
          </div>

          <div class="form-grid-2">
            <div>
              <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">EPS / Seguro</div>
              <div style="font-size: 14.5px; font-weight: 600; color: var(--secondary-color); margin-top: 3px;">{{ $trabajador->eps ?? 'No registrada' }}</div>
            </div>

            <div>
              <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">RH / Grupo Sanguíneo</div>
              <div style="font-size: 14.5px; font-weight: 600; color: var(--secondary-color); margin-top: 3px;">{{ $trabajador->rh ?? 'No registrado' }}</div>
            </div>

            <div>
              <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Teléfono</div>
              <div style="font-size: 14.5px; font-weight: 600; color: var(--secondary-color); margin-top: 3px;">{{ $trabajador->telefono ?? 'Sin teléfono' }}</div>
            </div>

            <div>
              <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Estado Actual</div>
              <div style="margin-top: 4px;">
                @if ($trabajador->is_active)
                  <span class="pill-status pill-status-active">ACTIVO</span>
                @else
                  <span class="pill-status pill-status-inactive">Inactivo</span>
                @endif
              </div>
            </div>

            <div>
              <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Fecha de Registro</div>
              <div style="font-size: 14.5px; font-weight: 600; color: var(--secondary-color); margin-top: 3px;">{{ $trabajador->formatted_fecha_registro }}</div>
            </div>
          </div>
        </div>

        <div class="af-modal-footer" style="justify-content: space-between;">
          <!-- Alternar Estado -->
          <form action="{{ route('trabajadores.toggle-status', $trabajador->id_trabajador) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn-outline" style="font-size: 13px; padding: 7px 14px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" x2="12" y1="2" y2="12"/></svg>
              <span>{{ $trabajador->is_active ? 'Desactivar Trabajador' : 'Activar Trabajador' }}</span>
            </button>
          </form>

          <div style="display: flex; gap: 10px;">
            <button type="button" class="btn-outline" data-af-modal-close="viewTrabajadorModal_{{ $trabajador->id_trabajador }}">Cerrar</button>
            <button type="button" class="btn-primary" onclick="closeAfModal('viewTrabajadorModal_{{ $trabajador->id_trabajador }}'); openAfModal('editTrabajadorModal_{{ $trabajador->id_trabajador }}');">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
              <span>Editar</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal: Editar Trabajador -->
    <div class="af-modal-overlay" id="editTrabajadorModal_{{ $trabajador->id_trabajador }}">
      <div class="af-modal-card">
        <div class="af-modal-header">
          <div class="af-modal-title-wrap">
            <div class="af-modal-icon-badge" style="background: #fffbeb; color: #b45309;">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
            </div>
            <div>
              <div class="af-modal-title">Editar Trabajador</div>
              <div style="font-size: 12.5px; color: var(--text-muted);">Modifica los datos de {{ $trabajador->name }}</div>
            </div>
          </div>
          <button type="button" class="af-modal-close-btn" data-af-modal-close="editTrabajadorModal_{{ $trabajador->id_trabajador }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
          </button>
        </div>

        <form action="{{ route('trabajadores.update', $trabajador->id_trabajador) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="af-modal-body">
            <div class="form-grid-2">
              <!-- Nombres -->
              <div class="form-group-af">
                <label class="form-label-af">Nombres <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  <input type="text" name="nombres" class="af-form-input" required value="{{ old('nombres', $trabajador->nombres) }}" />
                </div>
              </div>

              <!-- Apellidos -->
              <div class="form-group-af">
                <label class="form-label-af">Apellidos <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  <input type="text" name="apellidos" class="af-form-input" required value="{{ old('apellidos', $trabajador->apellidos) }}" />
                </div>
              </div>

              <!-- Documento -->
              <div class="form-group-af">
                <label class="form-label-af">Documento <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                  <input type="text" name="documento" class="af-form-input" required value="{{ old('documento', $trabajador->documento) }}" />
                </div>
              </div>

              <!-- Teléfono -->
              <div class="form-group-af">
                <label class="form-label-af">Teléfono <span class="form-label-optional">(Opcional)</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                  <input type="text" name="telefono" class="af-form-input" value="{{ old('telefono', $trabajador->telefono) }}" />
                </div>
              </div>

              <!-- EPS -->
              <div class="form-group-af">
                <label class="form-label-af">EPS / Seguro</label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                  <input type="text" name="eps" class="af-form-input" value="{{ old('eps', $trabajador->eps) }}" />
                </div>
              </div>

              <!-- RH -->
              <div class="form-group-af">
                <label class="form-label-af">RH / Grupo Sanguíneo</label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M2 12h20"/></svg>
                  <input type="text" name="rh" class="af-form-input" value="{{ old('rh', $trabajador->rh) }}" />
                </div>
              </div>

              <!-- Fecha de Ingreso -->
              <div class="form-group-af">
                <label class="form-label-af">Fecha de Ingreso</label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                  <input type="date" name="fecha_ingreso" class="af-form-input" value="{{ old('fecha_ingreso', $trabajador->fecha_ingreso) }}" />
                </div>
              </div>

              <!-- Estado -->
              <div class="form-group-af">
                <label class="form-label-af">Estado</label>
                <div class="af-input-wrapper">
                  <select name="estado_trabajador" class="af-form-input" style="padding-left: 16px;">
                    <option value="ACTIVO" {{ $trabajador->is_active ? 'selected' : '' }}>ACTIVO</option>
                    <option value="INACTIVO" {{ !$trabajador->is_active ? 'selected' : '' }}>INACTIVO</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="af-modal-footer">
            <button type="button" class="btn-outline" data-af-modal-close="editTrabajadorModal_{{ $trabajador->id_trabajador }}">Cancelar</button>
            <button type="submit" class="btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              <span>Guardar Cambios</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  @endforeach

</x-admin-layout>
