<x-admin-layout title="Gestión de Inventarios">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Gestión de Inventarios</h1>
    <p>Administra las bodegas de herramientas e insumos</p>
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

  <!-- Tarjetas KPI Resumen Superior (Idénticas al Screenshot) -->
  <div class="inv-kpi-grid">
    <!-- 1. Herramientas Disponibles -->
    <div class="inv-kpi-card green">
      <div class="inv-kpi-label">Herramientas Disponibles</div>
      <div class="inv-kpi-number">{{ $kpiDisponibles }}</div>
    </div>

    <!-- 2. En Mantenimiento -->
    <div class="inv-kpi-card amber">
      <div class="inv-kpi-label">En Mantenimiento</div>
      <div class="inv-kpi-number">{{ $kpiMantenimiento }}</div>
    </div>

    <!-- 3. Herramientas Dañadas -->
    <div class="inv-kpi-card red">
      <div class="inv-kpi-label">Herramientas Dañadas</div>
      <div class="inv-kpi-number">{{ $kpiDanadas }}</div>
    </div>

    <!-- 4. Insumos en Alerta -->
    <div class="inv-kpi-card blue">
      <div class="inv-kpi-label">Insumos en Alerta</div>
      <div class="inv-kpi-number">{{ $kpiInsumosAlerta }}</div>
    </div>
  </div>

  <!-- Pestañas Selectoras de Bodegas -->
  <div class="inv-tabs-nav">
    <a href="{{ route('inventario.index', ['tab' => 'herramientas']) }}" class="inv-tab-btn {{ $tab === 'herramientas' ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
      <span>Bodega 1 – Herramientas</span>
    </a>
    <a href="{{ route('inventario.index', ['tab' => 'insumos']) }}" class="inv-tab-btn {{ $tab === 'insumos' ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2-4.5 4.5"/><path d="m14 6 3 3"/><path d="M2 22l7-7"/><path d="M9 15l4 4"/><circle cx="10" cy="10" r="7"/></svg>
      <span>Bodega 2 – Insumos</span>
    </a>
  </div>

  <!-- ======================================================================
       PESTAÑA 1: BODEGA 1 - HERRAMIENTAS
       ====================================================================== -->
  @if ($tab === 'herramientas')
    <div class="inv-section-header">
      <h2 class="inv-section-title">Herramientas</h2>
      <button type="button" class="btn-primary" data-af-modal-open="createHerramientaModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
        <span>+ Registrar Herramienta</span>
      </button>
    </div>

    <!-- Tarjeta con Tabla de Herramientas -->
    <div class="af-table-card">
      <div class="af-table-responsive">
        <table class="af-table-data">
          <thead>
            <tr>
              <th style="width: 70px;">Foto</th>
              <th style="width: 60px;">ID</th>
              <th>Nombre</th>
              <th>Cantidad</th>
              <th>Estado</th>
              <th>Fecha Registro</th>
              <th style="text-align: center; width: 100px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($herramientas as $herramienta)
              <tr>
                <td>
                  @if ($herramienta->foto_referencia && file_exists(public_path($herramienta->foto_referencia)))
                    <img src="{{ asset($herramienta->foto_referencia) }}" alt="{{ $herramienta->nombre }}" class="inv-table-thumb" />
                  @else
                    <div class="inv-table-thumb">
                      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    </div>
                  @endif
                </td>
                <td style="font-weight: 600; color: var(--text-muted);">{{ $herramienta->id_herramienta }}</td>
                <td style="font-weight: 600; color: var(--secondary-color);">{{ $herramienta->nombre }}</td>
                <td style="font-weight: 500;">{{ $herramienta->cantidad_total }}</td>
                <td>
                  @if (strtolower($herramienta->estado ?? 'disponible') === 'disponible')
                    <span class="pill-status pill-status-active">Disponible</span>
                  @elseif (str_contains(strtolower($herramienta->estado ?? ''), 'mantenimiento'))
                    <span class="pill-status pill-status-amber">En Mantenimiento</span>
                  @else
                    <span class="pill-status pill-status-red">{{ $herramienta->estado ?? 'Dañada' }}</span>
                  @endif
                </td>
                <td>{{ $herramienta->fecha_registro_date }}</td>
                <td style="text-align: center;">
                  <div class="table-actions-cell" style="justify-content: center;">
                    <!-- Botón Editar -->
                    <button 
                      type="button" 
                      class="action-icon-btn btn-edit" 
                      title="Editar herramienta" 
                      data-af-modal-open="editHerramientaModal_{{ $herramienta->id_herramienta }}"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    </button>

                    <!-- Botón Eliminar -->
                    <form action="{{ route('inventario.herramientas.destroy', $herramienta->id_herramienta) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta herramienta del inventario?');" style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="action-icon-btn btn-delete" title="Eliminar herramienta">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7">
                  <div class="table-empty-state">
                    <div class="table-empty-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--secondary-color); margin-bottom: 6px;">No hay herramientas en Bodega 1</h3>
                    <p style="font-size: 14px;">Registra una nueva herramienta para controlar el inventario de implementos.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal 1: Registrar Herramienta -->
    <div class="af-modal-overlay" id="createHerramientaModal">
      <div class="af-modal-card">
        <div class="af-modal-header">
          <div class="af-modal-title-wrap">
            <div class="af-modal-icon-badge">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            </div>
            <div>
              <div class="af-modal-title">Registrar Herramienta</div>
              <div style="font-size: 12.5px; color: var(--text-muted);">Bodega 1 – Herramientas e Implementos</div>
            </div>
          </div>
          <button type="button" class="af-modal-close-btn" data-af-modal-close="createHerramientaModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
          </button>
        </div>

        <form action="{{ route('inventario.herramientas.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="af-modal-body">
            <div class="form-grid-2">
              <!-- Nombre -->
              <div class="form-group-af full-width">
                <label class="form-label-af">Nombre de la Herramienta <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                  <input type="text" name="nombre" class="af-form-input" placeholder="Ej. MACHETE 460 PULIDO" required value="{{ old('nombre') }}" />
                </div>
              </div>

              <!-- Cantidad Total -->
              <div class="form-group-af">
                <label class="form-label-af">Cantidad Total <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                  <input type="number" name="cantidad_total" class="af-form-input" placeholder="Ej. 10" min="0" required value="{{ old('cantidad_total', 1) }}" />
                </div>
              </div>

              <!-- Estado -->
              <div class="form-group-af">
                <label class="form-label-af">Estado</label>
                <div class="af-input-wrapper">
                  <select name="estado" class="af-form-input" style="padding-left: 16px;">
                    <option value="Disponible">Disponible</option>
                    <option value="En Mantenimiento">En Mantenimiento</option>
                    <option value="Dañada">Dañada</option>
                  </select>
                </div>
              </div>

              <!-- Fecha Registro -->
              <div class="form-group-af">
                <label class="form-label-af">Fecha de Registro</label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                  <input type="date" name="fecha_registro" class="af-form-input" value="{{ old('fecha_registro', date('Y-m-d')) }}" />
                </div>
              </div>

              <!-- Foto Referencia -->
              <div class="form-group-af">
                <label class="form-label-af">Foto de Referencia <span class="form-label-optional">(Opcional)</span></label>
                <div class="af-input-wrapper">
                  <input type="file" name="foto" accept="image/*" class="af-form-input" style="padding: 10px 16px;" />
                </div>
              </div>
            </div>
          </div>

          <div class="af-modal-footer">
            <button type="button" class="btn-outline" data-af-modal-close="createHerramientaModal">Cancelar</button>
            <button type="submit" class="btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              <span>Guardar Herramienta</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modales Dinámicos para Editar Herramienta -->
    @foreach ($herramientas as $herramienta)
      <div class="af-modal-overlay" id="editHerramientaModal_{{ $herramienta->id_herramienta }}">
        <div class="af-modal-card">
          <div class="af-modal-header">
            <div class="af-modal-title-wrap">
              <div class="af-modal-icon-badge" style="background: #fffbeb; color: #b45309;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
              </div>
              <div>
                <div class="af-modal-title">Editar Herramienta</div>
                <div style="font-size: 12.5px; color: var(--text-muted);">ID #{{ $herramienta->id_herramienta }} &bull; {{ $herramienta->nombre }}</div>
              </div>
            </div>
            <button type="button" class="af-modal-close-btn" data-af-modal-close="editHerramientaModal_{{ $herramienta->id_herramienta }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
          </div>

          <form action="{{ route('inventario.herramientas.update', $herramienta->id_herramienta) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="af-modal-body">
              <div class="form-grid-2">
                <!-- Nombre -->
                <div class="form-group-af full-width">
                  <label class="form-label-af">Nombre de la Herramienta <span style="color: #ef4444;">*</span></label>
                  <div class="af-input-wrapper">
                    <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    <input type="text" name="nombre" class="af-form-input" required value="{{ old('nombre', $herramienta->nombre) }}" />
                  </div>
                </div>

                <!-- Cantidad Total -->
                <div class="form-group-af">
                  <label class="form-label-af">Cantidad Total <span style="color: #ef4444;">*</span></label>
                  <div class="af-input-wrapper">
                    <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    <input type="number" name="cantidad_total" class="af-form-input" min="0" required value="{{ old('cantidad_total', $herramienta->cantidad_total) }}" />
                  </div>
                </div>

                <!-- Estado -->
                <div class="form-group-af">
                  <label class="form-label-af">Estado</label>
                  <div class="af-input-wrapper">
                    <select name="estado" class="af-form-input" style="padding-left: 16px;">
                      <option value="Disponible" {{ $herramienta->estado === 'Disponible' ? 'selected' : '' }}>Disponible</option>
                      <option value="En Mantenimiento" {{ $herramienta->estado === 'En Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                      <option value="Dañada" {{ $herramienta->estado === 'Dañada' ? 'selected' : '' }}>Dañada</option>
                    </select>
                  </div>
                </div>

                <!-- Fecha Registro -->
                <div class="form-group-af">
                  <label class="form-label-af">Fecha de Registro</label>
                  <div class="af-input-wrapper">
                    <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    <input type="date" name="fecha_registro" class="af-form-input" value="{{ old('fecha_registro', $herramienta->fecha_registro_date) }}" />
                  </div>
                </div>

                <!-- Foto Referencia -->
                <div class="form-group-af">
                  <label class="form-label-af">Actualizar Foto <span class="form-label-optional">(Opcional)</span></label>
                  <div class="af-input-wrapper">
                    <input type="file" name="foto" accept="image/*" class="af-form-input" style="padding: 10px 16px;" />
                  </div>
                </div>
              </div>
            </div>

            <div class="af-modal-footer">
              <button type="button" class="btn-outline" data-af-modal-close="editHerramientaModal_{{ $herramienta->id_herramienta }}">Cancelar</button>
              <button type="submit" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>Guardar Cambios</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    @endforeach

  <!-- ======================================================================
       PESTAÑA 2: BODEGA 2 - INSUMOS
       ====================================================================== -->
  @elseif ($tab === 'insumos')
    <div class="inv-section-header">
      <h2 class="inv-section-title">Insumos</h2>
      <button type="button" class="btn-primary" data-af-modal-open="createInsumoModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
        <span>+ Registrar Insumo</span>
      </button>
    </div>

    <!-- Tarjeta con Tabla de Insumos -->
    <div class="af-table-card">
      <div class="af-table-responsive">
        <table class="af-table-data">
          <thead>
            <tr>
              <th style="width: 70px;">Foto</th>
              <th style="width: 60px;">ID</th>
              <th>Nombre</th>
              <th>Stock Actual</th>
              <th>Unidad</th>
              <th>Stock Mínimo</th>
              <th>Vencimiento</th>
              <th>Estado</th>
              <th>Fecha Registro</th>
              <th style="text-align: center; width: 100px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($insumos as $insumo)
              <tr>
                <td>
                  @if ($insumo->foto_referencia && file_exists(public_path($insumo->foto_referencia)))
                    <img src="{{ asset($insumo->foto_referencia) }}" alt="{{ $insumo->nombre }}" class="inv-table-thumb" />
                  @else
                    <div class="inv-table-thumb">
                      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2-4.5 4.5"/><path d="m14 6 3 3"/><path d="M2 22l7-7"/><path d="M9 15l4 4"/><circle cx="10" cy="10" r="7"/></svg>
                    </div>
                  @endif
                </td>
                <td style="font-weight: 600; color: var(--text-muted);">{{ $insumo->id_insumo }}</td>
                <td style="font-weight: 600; color: var(--secondary-color);">{{ $insumo->nombre }}</td>
                <td style="font-weight: 700; color: var(--secondary-color);">{{ number_format($insumo->stock_actual, 2) }}</td>
                <td>{{ $insumo->unidad_medida ?? 'unidad' }}</td>
                <td>{{ number_format($insumo->cantidad_minima, 2) }}</td>
                <td>{{ $insumo->fecha_vencimiento_date ?? 'No aplica' }}</td>
                <td>
                  @if ($insumo->estado_calculado === 'Normal')
                    <span class="pill-status pill-status-active">Normal</span>
                  @elseif ($insumo->estado_calculado === 'Bajo Stock')
                    <span class="pill-status pill-status-amber">Bajo Stock</span>
                  @else
                    <span class="pill-status pill-status-red">Vencido</span>
                  @endif
                </td>
                <td>{{ $insumo->fecha_registro_date }}</td>
                <td style="text-align: center;">
                  <div class="table-actions-cell" style="justify-content: center;">
                    <!-- Botón Editar -->
                    <button 
                      type="button" 
                      class="action-icon-btn btn-edit" 
                      title="Editar insumo" 
                      data-af-modal-open="editInsumoModal_{{ $insumo->id_insumo }}"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    </button>

                    <!-- Botón Eliminar -->
                    <form action="{{ route('inventario.insumos.destroy', $insumo->id_insumo) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este insumo del inventario?');" style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="action-icon-btn btn-delete" title="Eliminar insumo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="10">
                  <div class="table-empty-state">
                    <div class="table-empty-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2-4.5 4.5"/><path d="m14 6 3 3"/><path d="M2 22l7-7"/><path d="M9 15l4 4"/><circle cx="10" cy="10" r="7"/></svg>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--secondary-color); margin-bottom: 6px;">No hay insumos en Bodega 2</h3>
                    <p style="font-size: 14px;">Registra fertilizantes, abonos o químicos para controlar los insumos de los cultivos.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal 1: Registrar Insumo -->
    <div class="af-modal-overlay" id="createInsumoModal">
      <div class="af-modal-card">
        <div class="af-modal-header">
          <div class="af-modal-title-wrap">
            <div class="af-modal-icon-badge">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2-4.5 4.5"/><path d="m14 6 3 3"/><path d="M2 22l7-7"/><path d="M9 15l4 4"/><circle cx="10" cy="10" r="7"/></svg>
            </div>
            <div>
              <div class="af-modal-title">Registrar Insumo</div>
              <div style="font-size: 12.5px; color: var(--text-muted);">Bodega 2 – Fertilizantes y Agroquímicos</div>
            </div>
          </div>
          <button type="button" class="af-modal-close-btn" data-af-modal-close="createInsumoModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
          </button>
        </div>

        <form action="{{ route('inventario.insumos.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="af-modal-body">
            <div class="form-grid-2">
              <!-- Nombre -->
              <div class="form-group-af full-width">
                <label class="form-label-af">Nombre del Insumo <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 2-4.5 4.5"/><path d="m14 6 3 3"/><path d="M2 22l7-7"/><path d="M9 15l4 4"/><circle cx="10" cy="10" r="7"/></svg>
                  <input type="text" name="nombre" class="af-form-input" placeholder="Ej. AGROCOSECHA Fertilizante Café" required value="{{ old('nombre') }}" />
                </div>
              </div>

              <!-- Stock Actual -->
              <div class="form-group-af">
                <label class="form-label-af">Stock Actual <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                  <input type="number" step="0.01" name="stock_actual" class="af-form-input" placeholder="Ej. 5.00" min="0" required value="{{ old('stock_actual', 0) }}" />
                </div>
              </div>

              <!-- Unidad de Medida -->
              <div class="form-group-af">
                <label class="form-label-af">Unidad de Medida <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <select name="unidad_medida" class="af-form-input" style="padding-left: 16px;">
                    <option value="arrobas">Arrobas</option>
                    <option value="bultos">Bultos</option>
                    <option value="kg">Kilogramos (kg)</option>
                    <option value="litros">Litros (L)</option>
                    <option value="galones">Galones</option>
                    <option value="gramos">Gramos (g)</option>
                    <option value="unidades">Unidades</option>
                  </select>
                </div>
              </div>

              <!-- Stock Mínimo -->
              <div class="form-group-af">
                <label class="form-label-af">Stock Mínimo (Alerta) <span style="color: #ef4444;">*</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                  <input type="number" step="0.01" name="cantidad_minima" class="af-form-input" placeholder="Ej. 2.00" min="0" required value="{{ old('cantidad_minima', 2) }}" />
                </div>
              </div>

              <!-- Fecha Vencimiento -->
              <div class="form-group-af">
                <label class="form-label-af">Fecha de Vencimiento <span class="form-label-optional">(Opcional)</span></label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                  <input type="date" name="fecha_vencimiento" class="af-form-input" value="{{ old('fecha_vencimiento') }}" />
                </div>
              </div>

              <!-- Fecha Registro -->
              <div class="form-group-af">
                <label class="form-label-af">Fecha de Registro</label>
                <div class="af-input-wrapper">
                  <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                  <input type="date" name="fecha_registro" class="af-form-input" value="{{ old('fecha_registro', date('Y-m-d')) }}" />
                </div>
              </div>

              <!-- Foto Referencia -->
              <div class="form-group-af">
                <label class="form-label-af">Foto de Referencia <span class="form-label-optional">(Opcional)</span></label>
                <div class="af-input-wrapper">
                  <input type="file" name="foto" accept="image/*" class="af-form-input" style="padding: 10px 16px;" />
                </div>
              </div>
            </div>
          </div>

          <div class="af-modal-footer">
            <button type="button" class="btn-outline" data-af-modal-close="createInsumoModal">Cancelar</button>
            <button type="submit" class="btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              <span>Guardar Insumo</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modales Dinámicos para Editar Insumo -->
    @foreach ($insumos as $insumo)
      <div class="af-modal-overlay" id="editInsumoModal_{{ $insumo->id_insumo }}">
        <div class="af-modal-card">
          <div class="af-modal-header">
            <div class="af-modal-title-wrap">
              <div class="af-modal-icon-badge" style="background: #fffbeb; color: #b45309;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
              </div>
              <div>
                <div class="af-modal-title">Editar Insumo</div>
                <div style="font-size: 12.5px; color: var(--text-muted);">ID #{{ $insumo->id_insumo }} &bull; {{ $insumo->nombre }}</div>
              </div>
            </div>
            <button type="button" class="af-modal-close-btn" data-af-modal-close="editInsumoModal_{{ $insumo->id_insumo }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
          </div>

          <form action="{{ route('inventario.insumos.update', $insumo->id_insumo) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="af-modal-body">
              <div class="form-grid-2">
                <!-- Nombre -->
                <div class="form-group-af full-width">
                  <label class="form-label-af">Nombre del Insumo <span style="color: #ef4444;">*</span></label>
                  <div class="af-input-wrapper">
                    <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 2-4.5 4.5"/><path d="m14 6 3 3"/><path d="M2 22l7-7"/><path d="M9 15l4 4"/><circle cx="10" cy="10" r="7"/></svg>
                    <input type="text" name="nombre" class="af-form-input" required value="{{ old('nombre', $insumo->nombre) }}" />
                  </div>
                </div>

                <!-- Stock Actual -->
                <div class="form-group-af">
                  <label class="form-label-af">Stock Actual <span style="color: #ef4444;">*</span></label>
                  <div class="af-input-wrapper">
                    <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    <input type="number" step="0.01" name="stock_actual" class="af-form-input" min="0" required value="{{ old('stock_actual', $insumo->stock_actual) }}" />
                  </div>
                </div>

                <!-- Unidad de Medida -->
                <div class="form-group-af">
                  <label class="form-label-af">Unidad de Medida <span style="color: #ef4444;">*</span></label>
                  <div class="af-input-wrapper">
                    <select name="unidad_medida" class="af-form-input" style="padding-left: 16px;">
                      <option value="arrobas" {{ $insumo->unidad_medida === 'arrobas' ? 'selected' : '' }}>Arrobas</option>
                      <option value="bultos" {{ $insumo->unidad_medida === 'bultos' ? 'selected' : '' }}>Bultos</option>
                      <option value="kg" {{ $insumo->unidad_medida === 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                      <option value="litros" {{ $insumo->unidad_medida === 'litros' ? 'selected' : '' }}>Litros (L)</option>
                      <option value="galones" {{ $insumo->unidad_medida === 'galones' ? 'selected' : '' }}>Galones</option>
                      <option value="gramos" {{ $insumo->unidad_medida === 'gramos' ? 'selected' : '' }}>Gramos (g)</option>
                      <option value="unidades" {{ $insumo->unidad_medida === 'unidades' ? 'selected' : '' }}>Unidades</option>
                    </select>
                  </div>
                </div>

                <!-- Stock Mínimo -->
                <div class="form-group-af">
                  <label class="form-label-af">Stock Mínimo (Alerta) <span style="color: #ef4444;">*</span></label>
                  <div class="af-input-wrapper">
                    <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <input type="number" step="0.01" name="cantidad_minima" class="af-form-input" min="0" required value="{{ old('cantidad_minima', $insumo->cantidad_minima) }}" />
                  </div>
                </div>

                <!-- Fecha Vencimiento -->
                <div class="form-group-af">
                  <label class="form-label-af">Fecha de Vencimiento</label>
                  <div class="af-input-wrapper">
                    <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    <input type="date" name="fecha_vencimiento" class="af-form-input" value="{{ old('fecha_vencimiento', $insumo->fecha_vencimiento_date) }}" />
                  </div>
                </div>

                <!-- Fecha Registro -->
                <div class="form-group-af">
                  <label class="form-label-af">Fecha de Registro</label>
                  <div class="af-input-wrapper">
                    <svg class="af-input-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    <input type="date" name="fecha_registro" class="af-form-input" value="{{ old('fecha_registro', $insumo->fecha_registro_date) }}" />
                  </div>
                </div>

                <!-- Foto Referencia -->
                <div class="form-group-af">
                  <label class="form-label-af">Actualizar Foto <span class="form-label-optional">(Opcional)</span></label>
                  <div class="af-input-wrapper">
                    <input type="file" name="foto" accept="image/*" class="af-form-input" style="padding: 10px 16px;" />
                  </div>
                </div>
              </div>
            </div>

            <div class="af-modal-footer">
              <button type="button" class="btn-outline" data-af-modal-close="editInsumoModal_{{ $insumo->id_insumo }}">Cancelar</button>
              <button type="submit" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>Guardar Cambios</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    @endforeach
  @endif

</x-admin-layout>
