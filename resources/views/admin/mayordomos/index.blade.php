<x-admin-layout title="Gestión de Mayordomos">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Gestión de Mayordomos</h1>
    <p>Administra los mayordomos del sistema</p>
  </x-slot>

  <!-- Acciones Superiores -->
  <x-slot name="actions">
    <button type="button" class="btn-primary" data-af-modal-open="createMayordomoModal">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
      <span>+ Registrar Nuevo Mayordomo</span>
    </button>
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

  <!-- Barra de Búsqueda Interactiva -->
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

  <!-- Tarjeta con Tabla de Datos -->
  <div class="af-table-card">
    <div class="af-table-responsive">
      <table class="af-table-data">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Documento</th>
            <th>Usuario</th>
            <th>Estado</th>
            <th>Fecha Creación</th>
            <th style="text-align: center;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($mayordomos as $mayordomo)
            <tr>
              <td style="font-weight: 500;">{{ $mayordomo->nombres }}</td>
              <td>{{ $mayordomo->apellidos }}</td>
              <td>{{ $mayordomo->documento }}</td>
              <td>{{ $mayordomo->username }}</td>
              <td>
                @if ($mayordomo->activo)
                  <span class="pill-status pill-status-active">Activo</span>
                @else
                  <span class="pill-status pill-status-inactive">Inactivo</span>
                @endif
              </td>
              <td>{{ $mayordomo->fecha_creacion_date }}</td>
              <td style="text-align: center;">
                <div class="table-actions-cell" style="justify-content: center;">
                  <!-- Botón Ver Detalles -->
                  <button 
                    type="button" 
                    class="action-icon-btn btn-view" 
                    title="Ver detalles" 
                    data-af-modal-open="viewModal_{{ $mayordomo->id_usuario }}"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>

                  <!-- Botón Editar -->
                  <button 
                    type="button" 
                    class="action-icon-btn btn-edit" 
                    title="Editar mayordomo" 
                    data-af-modal-open="editModal_{{ $mayordomo->id_usuario }}"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7">
                <div class="table-empty-state">
                  <div class="table-empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="17" x2="23" y1="8" y2="14"/><line x1="23" x2="17" y1="8" y2="14"/></svg>
                  </div>
                  <h3 style="font-size: 16px; font-weight: 700; color: var(--secondary-color); margin-bottom: 6px;">No se encontraron mayordomos</h3>
                  <p style="font-size: 14px;">Registra un nuevo mayordomo para comenzar a gestionar sus labores.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if ($mayordomos->hasPages())
    <div style="margin-top: 20px;">
      {{ $mayordomos->links() }}
    </div>
  @endif

  <!-- ======================================================================
       MODAL 1: REGISTRAR NUEVO MAYORDOMO
       ====================================================================== -->
  <div class="af-modal-overlay" id="createMayordomoModal">
    <div class="af-modal-card">
      <div class="af-modal-header">
        <div class="af-modal-title-wrap">
          <div class="af-modal-icon-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
          </div>
          <div>
            <div class="af-modal-title">Registrar Nuevo Mayordomo</div>
            <div style="font-size: 12.5px; color: var(--text-muted);">Completa la información del mayordomo</div>
          </div>
        </div>
        <button type="button" class="af-modal-close-btn" data-af-modal-close="createMayordomoModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
        </button>
      </div>

      <form action="{{ route('mayordomos.store') }}" method="POST">
        @csrf
        <div class="af-modal-body">
          <div class="form-grid-2">
            <!-- Nombres -->
            <div class="form-group-af">
              <label for="new_nombres" class="form-label-af">Nombres <span style="color: #ef4444;">*</span></label>
              <div class="af-input-wrapper">
                <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input type="text" id="new_nombres" name="nombres" class="af-form-input" placeholder="Ej. Paola" required value="{{ old('nombres') }}" />
              </div>
            </div>

            <!-- Apellidos -->
            <div class="form-group-af">
              <label for="new_apellidos" class="form-label-af">Apellidos <span style="color: #ef4444;">*</span></label>
              <div class="af-input-wrapper">
                <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input type="text" id="new_apellidos" name="apellidos" class="af-form-input" placeholder="Ej. Garcia" required value="{{ old('apellidos') }}" />
              </div>
            </div>

            <!-- Documento -->
            <div class="form-group-af">
              <label for="new_documento" class="form-label-af">Documento <span style="color: #ef4444;">*</span></label>
              <div class="af-input-wrapper">
                <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                <input type="text" id="new_documento" name="documento" class="af-form-input" placeholder="Ej. 1236" required value="{{ old('documento') }}" />
              </div>
            </div>

            <!-- Teléfono -->
            <div class="form-group-af">
              <label for="new_telefono" class="form-label-af">Teléfono <span class="form-label-optional">(Opcional)</span></label>
              <div class="af-input-wrapper">
                <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <input type="text" id="new_telefono" name="telefono" class="af-form-input" placeholder="Ej. 3123456789" value="{{ old('telefono') }}" />
              </div>
            </div>

            <!-- Usuario -->
            <div class="form-group-af">
              <label for="new_username" class="form-label-af">Usuario <span style="color: #ef4444;">*</span></label>
              <div class="af-input-wrapper">
                <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
                <input type="text" id="new_username" name="username" class="af-form-input" placeholder="Ej. paola" required value="{{ old('username') }}" />
              </div>
            </div>

            <!-- Contraseña -->
            <div class="form-group-af">
              <label for="new_password" class="form-label-af">Contraseña <span style="color: #ef4444;">*</span></label>
              <div class="af-input-wrapper">
                <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input type="password" id="new_password" name="password" class="af-form-input" placeholder="Mínimo 6 caracteres" required />
              </div>
            </div>

            <!-- Estado Activo -->
            <div class="form-group-af full-width" style="margin-top: 6px;">
              <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px; font-weight: 600; color: var(--secondary-color);">
                <input type="checkbox" name="activo" value="1" checked style="width: 18px; height: 18px; accent-color: var(--primary-color); cursor: pointer;" />
                <span>Marcar como Mayordomo Activo</span>
              </label>
            </div>
          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-outline" data-af-modal-close="createMayordomoModal">Cancelar</button>
          <button type="submit" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Guardar Mayordomo</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ======================================================================
       MODALES DINÁMICOS POR REGISTRO (EDITAR Y DETALLES)
       ====================================================================== -->
  @foreach ($mayordomos as $mayordomo)
    <!-- Modal: Editar Mayordomo -->
    <div class="af-modal-overlay" id="editModal_{{ $mayordomo->id_usuario }}">
      <div class="af-modal-card">
        <div class="af-modal-header">
          <div class="af-modal-title-wrap">
            <div class="af-modal-icon-badge" style="background: #fffbeb; color: #b45309;">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
            </div>
            <div>
              <div class="af-modal-title">Editar Mayordomo</div>
              <div style="font-size: 12.5px; color: var(--text-muted);">Modifica los datos de {{ $mayordomo->name }}</div>
            </div>
          </div>
          <button type="button" class="af-modal-close-btn" data-af-modal-close="editModal_{{ $mayordomo->id_usuario }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
          </button>
        </div>

        <form action="{{ route('mayordomos.update', $mayordomo->id_usuario) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="af-modal-body">
            <div class="form-grid-2">
              <!-- Nombres -->
              <div class="form-group-af">
                <label class="form-label-af">Nombres <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  <input type="text" name="nombres" class="af-form-input" required value="{{ old('nombres', $mayordomo->nombres) }}" />
                </div>
              </div>

              <!-- Apellidos -->
              <div class="form-group-af">
                <label class="form-label-af">Apellidos <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  <input type="text" name="apellidos" class="af-form-input" required value="{{ old('apellidos', $mayordomo->apellidos) }}" />
                </div>
              </div>

              <!-- Documento -->
              <div class="form-group-af">
                <label class="form-label-af">Documento <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                  <input type="text" name="documento" class="af-form-input" required value="{{ old('documento', $mayordomo->documento) }}" />
                </div>
              </div>

              <!-- Teléfono -->
              <div class="form-group-af">
                <label class="form-label-af">Teléfono <span class="form-label-optional">(Opcional)</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                  <input type="text" name="telefono" class="af-form-input" value="{{ old('telefono', $mayordomo->telefono) }}" />
                </div>
              </div>

              <!-- Usuario -->
              <div class="form-group-af">
                <label class="form-label-af">Usuario <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
                  <input type="text" name="username" class="af-form-input" required value="{{ old('username', $mayordomo->username) }}" />
                </div>
              </div>

              <!-- Nueva Contraseña -->
              <div class="form-group-af">
                <label class="form-label-af">Nueva Contraseña <span class="form-label-optional">(Dejar vacía para mantener)</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                  <input type="password" name="password" class="af-form-input" placeholder="Nueva contraseña opcional" />
                </div>
              </div>

              <!-- Estado Activo -->
              <div class="form-group-af full-width" style="margin-top: 6px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px; font-weight: 600; color: var(--secondary-color);">
                  <input type="checkbox" name="activo" value="1" {{ $mayordomo->activo ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary-color); cursor: pointer;" />
                  <span>Mayordomo Activo</span>
                </label>
              </div>
            </div>
          </div>

          <div class="af-modal-footer">
            <button type="button" class="btn-outline" data-af-modal-close="editModal_{{ $mayordomo->id_usuario }}">Cancelar</button>
            <button type="submit" class="btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              <span>Actualizar Cambios</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal: Ver Detalles del Mayordomo -->
    <div class="af-modal-overlay" id="viewModal_{{ $mayordomo->id_usuario }}">
      <div class="af-modal-card">
        <div class="af-modal-header">
          <div class="af-modal-title-wrap">
            <div class="af-modal-icon-badge">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <div>
              <div class="af-modal-title">Ficha del Mayordomo</div>
              <div style="font-size: 12.5px; color: var(--text-muted);">Información registrada en AgroFinca</div>
            </div>
          </div>
          <button type="button" class="af-modal-close-btn" data-af-modal-close="viewModal_{{ $mayordomo->id_usuario }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
          </button>
        </div>

        <div class="af-modal-body">
          <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
            <div style="width: 58px; height: 58px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800;">
              {{ $mayordomo->initials }}
            </div>
            <div>
              <h3 style="font-size: 18px; font-weight: 800; color: var(--secondary-color);">{{ $mayordomo->name }}</h3>
              <div style="font-size: 13.5px; color: var(--text-muted);">{{ '@' . $mayordomo->username }} &bull; <span class="badge" style="font-size: 11px; padding: 2px 8px;">MAYORDOMO</span></div>
            </div>
          </div>

          <div class="form-grid-2">
            <div>
              <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Documento de Identidad</div>
              <div style="font-size: 14.5px; font-weight: 600; color: var(--secondary-color); margin-top: 3px;">{{ $mayordomo->documento }}</div>
            </div>

            <div>
              <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Teléfono de Contacto</div>
              <div style="font-size: 14.5px; font-weight: 600; color: var(--secondary-color); margin-top: 3px;">{{ $mayordomo->telefono ?? 'Sin teléfono' }}</div>
            </div>

            <div>
              <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Estado en Plataforma</div>
              <div style="margin-top: 4px;">
                @if ($mayordomo->activo)
                  <span class="pill-status pill-status-active">Activo</span>
                @else
                  <span class="pill-status pill-status-inactive">Inactivo</span>
                @endif
              </div>
            </div>

            <div>
              <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Fecha de Registro</div>
              <div style="font-size: 14.5px; font-weight: 600; color: var(--secondary-color); margin-top: 3px;">{{ $mayordomo->formatted_fecha_creacion }}</div>
            </div>
          </div>
        </div>

        <div class="af-modal-footer" style="justify-content: space-between;">
          <!-- Toggle Activo / Inactivo -->
          <form action="{{ route('mayordomos.toggle-status', $mayordomo->id_usuario) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn-outline" style="font-size: 13px; padding: 7px 14px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" x2="12" y1="2" y2="12"/></svg>
              <span>{{ $mayordomo->activo ? 'Desactivar Mayordomo' : 'Activar Mayordomo' }}</span>
            </button>
          </form>

          <div style="display: flex; gap: 10px;">
            <button type="button" class="btn-outline" data-af-modal-close="viewModal_{{ $mayordomo->id_usuario }}">Cerrar</button>
            <button type="button" class="btn-primary" onclick="closeAfModal('viewModal_{{ $mayordomo->id_usuario }}'); openAfModal('editModal_{{ $mayordomo->id_usuario }}');">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
              <span>Editar</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  @endforeach

</x-admin-layout>
