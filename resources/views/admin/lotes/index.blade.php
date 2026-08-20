<x-admin-layout title="Lotes y Producción">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Lotes y Producción</h1>
    <p>Vista estratégica de lotes, cultivos y producción de la finca</p>
  </x-slot>

  <!-- Botones de Acción Superiores -->
  <x-slot name="actions">
    <button type="button" class="btn-outline" data-af-modal-open="createCultivoModal">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a9 9 0 0 1 9 9c0 4.97-4.03 9-9 9A9 9 0 0 1 3 11a9 9 0 0 1 9-9Z"/><path d="M12 7v10"/><path d="M8 12h8"/></svg>
      <span>+ Nuevo Cultivo</span>
    </button>
    <button type="button" class="btn-outline" data-af-modal-open="createProduccionModal">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
      <span>+ Registrar Cosecha</span>
    </button>
    <button type="button" class="btn-primary" data-af-modal-open="createLoteModal">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
      <span>+ Registrar Lote</span>
    </button>
  </x-slot>

  <!-- Notificaciones Flash -->
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

  <!-- ======================================================================
       PESTAÑAS DE NAVEGACIÓN: LOTES | CULTIVOS POR LOTE | PRODUCCIÓN POR LOTE
       ====================================================================== -->
  <nav class="lotes-tabs-nav">
    <a href="{{ route('cultivos.index', ['tab' => 'lotes']) }}" class="lotes-tab-item {{ $tab === 'lotes' ? 'active' : '' }}">
      <span>🌍</span>
      <span>Lotes</span>
    </a>
    <a href="{{ route('cultivos.index', ['tab' => 'cultivos']) }}" class="lotes-tab-item {{ $tab === 'cultivos' ? 'active' : '' }}">
      <span>🌾</span>
      <span>Cultivos por Lote</span>
    </a>
    <a href="{{ route('cultivos.index', ['tab' => 'produccion']) }}" class="lotes-tab-item {{ $tab === 'produccion' ? 'active' : '' }}">
      <span>📈</span>
      <span>Producción por Lote</span>
    </a>
  </nav>

  <!-- ======================================================================
       PESTAÑA 1: LOTES (TABLA Y KPIS)
       ====================================================================== -->
  @if ($tab === 'lotes')
    <!-- Tarjetas KPI Resumen Superior -->
    <div class="lotes-kpi-grid">
      <!-- 1. Total Lotes -->
      <div class="lotes-kpi-card green">
        <div class="lotes-kpi-label">Total Lotes</div>
        <div class="lotes-kpi-number">{{ $totalLotes }}</div>
      </div>

      <!-- 2. Extensión Total -->
      <div class="lotes-kpi-card blue">
        <div class="lotes-kpi-label">Extensión Total</div>
        <div class="lotes-kpi-number">{{ $extensionTotal }}</div>
      </div>
    </div>

    <!-- Tabla Principal de Lotes -->
    <div class="af-table-card">
      <div class="af-table-responsive">
        <table class="af-table-data">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Ubicación</th>
              <th>Extensión</th>
              <th>Tipo Cultivo</th>
              <th>Producción Total</th>
              <th>Fecha Registro</th>
              <th style="text-align: right; padding-right: 24px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($lotes as $lote)
              <tr>
                <td style="font-weight: 600; color: var(--secondary-color);">{{ $lote->nombre }}</td>
                <td>{{ $lote->ubicacion_descripcion ?? '-' }}</td>
                <td>{{ $lote->formatted_extension }}</td>
                <td>
                  <span class="pill-crop">{{ $lote->cultivo->nombre ?? 'general' }}</span>
                </td>
                <td style="font-weight: 500;">{{ $lote->produccion_total }}</td>
                <td>{{ $lote->formatted_fecha_registro }}</td>
                <td style="text-align: right; padding-right: 24px;">
                  <button type="button" class="btn-ver-detalles" data-af-modal-open="viewLoteModal_{{ $lote->id_lote }}">
                    <span>Ver detalles</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="table-empty-state">
                  <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a9 9 0 0 1 9 9c0 4.97-4.03 9-9 9A9 9 0 0 1 3 11a9 9 0 0 1 9-9Z"/><path d="M12 7v10"/><path d="M8 12h8"/></svg>
                  <p style="margin-top: 8px; color: var(--text-muted);">No hay lotes registrados actualmente.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  @endif

  <!-- ======================================================================
       PESTAÑA 2: CULTIVOS POR LOTE (TARJETAS)
       ====================================================================== -->
  @if ($tab === 'cultivos')
    <div class="lotes-tab-header">
      <h3 class="lotes-tab-title">Cultivos por Lote</h3>
      <p class="lotes-tab-subtitle">Selecciona un lote para ver sus cultivos y especificaciones</p>
    </div>

    <div class="cultivos-cards-grid">
      @forelse ($lotes as $lote)
        <div class="cultivo-lote-card" data-af-modal-open="viewLoteModal_{{ $lote->id_lote }}">
          <div class="cultivo-card-top">
            <div class="cultivo-card-name">{{ $lote->nombre }}</div>
            <span class="pill-crop">{{ $lote->cultivo->nombre ?? 'general' }}</span>
          </div>
          <div class="cultivo-card-location">
            {{ $lote->ubicacion_descripcion ?? 'Sin ubicación' }} - {{ $lote->formatted_extension }}
          </div>
          <div class="cultivo-card-bottom">
            <div class="cultivo-card-meta">
              1 variedades cultivadas ({{ $lote->cultivo->variedad ?? 'Tradicional' }})
            </div>
            <div class="cultivo-card-arrow">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </div>
          </div>
        </div>
      @empty
        <div style="grid-column: 1 / -1;" class="af-table-card">
          <div class="table-empty-state" style="padding: 40px;">
            <p style="color: var(--text-muted);">No hay lotes ni cultivos registrados.</p>
          </div>
        </div>
      @endforelse
    </div>
  @endif

  <!-- ======================================================================
       PESTAÑA 3: PRODUCCIÓN POR LOTE (TARJETAS CON ICONO DE BARRAS)
       ====================================================================== -->
  @if ($tab === 'produccion')
    <div class="produccion-cards-grid">
      @forelse ($lotes as $lote)
        <div class="produccion-lote-card" data-af-modal-open="viewLoteModal_{{ $lote->id_lote }}" style="cursor: pointer;">
          <div class="produccion-card-info">
            <h4>{{ $lote->nombre }}</h4>
            <div class="produccion-card-crop">{{ $lote->cultivo->nombre ?? 'general' }}</div>
            <div class="produccion-card-stat">
              Producción total: <strong>{{ $lote->produccion_total }}</strong>
            </div>
            <div class="produccion-card-stat">
              Registros: <strong>{{ $lote->producciones->count() }}</strong>
            </div>
          </div>

          <!-- Icono de Gráfico de Barras Colorido -->
          <div class="produccion-chart-icon" title="Estadísticas de Producción">
            <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="5" y="14" width="5" height="14" rx="2" fill="#10b981"/>
              <rect x="13" y="8" width="5" height="20" rx="2" fill="#3b82f6"/>
              <rect x="21" y="4" width="5" height="24" rx="2" fill="#8b5cf6"/>
              <rect x="27" y="16" width="3" height="12" rx="1.5" fill="#f43f5e"/>
            </svg>
          </div>
        </div>
      @empty
        <div style="grid-column: 1 / -1;" class="af-table-card">
          <div class="table-empty-state" style="padding: 40px;">
            <p style="color: var(--text-muted);">No hay producciones registradas aún.</p>
          </div>
        </div>
      @endforelse
    </div>
  @endif

  <!-- ======================================================================
       MODALES DEL MÓDULO (DETALLES, REGISTRO, EDICIÓN Y PRODUCCIÓN)
       ====================================================================== -->

  <!-- 1. Modal: Registrar Nuevo Lote -->
  <div class="af-modal-overlay" id="createLoteModal">
    <div class="af-modal-card" style="max-width: 580px;">
      <div class="af-modal-header">
        <div class="af-modal-title">
          <div class="modal-title-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a9 9 0 0 1 9 9c0 4.97-4.03 9-9 9A9 9 0 0 1 3 11a9 9 0 0 1 9-9Z"/><path d="M12 7v10"/><path d="M8 12h8"/></svg>
          </div>
          <div>
            <h3>Registrar Nuevo Lote</h3>
            <p>Ingresa la información geográfica y agrícola del lote</p>
          </div>
        </div>
        <button type="button" class="btn-close-modal" data-af-modal-close="createLoteModal">&times;</button>
      </div>

      <form action="{{ route('lotes.store') }}" method="POST">
        @csrf
        <div class="af-modal-body">
          <div class="af-form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="af-form-group">
              <label for="lote_nombre">Nombre del Lote <span class="req">*</span></label>
              <input type="text" id="lote_nombre" name="nombre" class="af-form-input" placeholder="Ej: norte, sur, lote 1..." required />
            </div>

            <div class="af-form-group">
              <label for="lote_extension">Extensión (Hectáreas) <span class="req">*</span></label>
              <input type="number" step="0.01" id="lote_extension" name="extension" class="af-form-input" placeholder="Ej: 5.00" required />
            </div>

            <div class="af-form-group" style="grid-column: span 2;">
              <label for="lote_ubicacion">Ubicación / Descripción <span class="req">*</span></label>
              <input type="text" id="lote_ubicacion" name="ubicacion_descripcion" class="af-form-input" placeholder="Ej: zona A, sector alto..." required />
            </div>

            <div class="af-form-group">
              <label for="lote_cultivo">Cultivo Principal <span class="req">*</span></label>
              <select id="lote_cultivo" name="id_cultivo" class="af-form-input" required>
                <option value="" disabled selected>Selecciona un cultivo</option>
                @foreach ($cultivos as $cultivo)
                  <option value="{{ $cultivo->id_cultivo }}">{{ ucfirst($cultivo->nombre) }} ({{ $cultivo->variedad }})</option>
                @endforeach
              </select>
            </div>

            <div class="af-form-group">
              <label for="lote_fecha">Fecha de Registro</label>
              <input type="date" id="lote_fecha" name="fecha_registro" class="af-form-input" value="{{ date('Y-m-d') }}" />
            </div>
          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-cancel" data-af-modal-close="createLoteModal">Cancelar</button>
          <button type="submit" class="btn-save">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <span>Guardar Lote</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 2. Modal: Registrar Nuevo Cultivo -->
  <div class="af-modal-overlay" id="createCultivoModal">
    <div class="af-modal-card" style="max-width: 540px;">
      <div class="af-modal-header">
        <div class="af-modal-title">
          <div class="modal-title-icon amber">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a9 9 0 0 1 9 9c0 4.97-4.03 9-9 9A9 9 0 0 1 3 11a9 9 0 0 1 9-9Z"/><path d="M12 7v10"/><path d="M8 12h8"/></svg>
          </div>
          <div>
            <h3>Registrar Tipo de Cultivo</h3>
            <p>Añade un nuevo tipo de cultivo al catálogo agrícola</p>
          </div>
        </div>
        <button type="button" class="btn-close-modal" data-af-modal-close="createCultivoModal">&times;</button>
      </div>

      <form action="{{ route('cultivos.store') }}" method="POST">
        @csrf
        <div class="af-modal-body">
          <div class="af-form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="af-form-group">
              <label for="cultivo_nombre">Nombre del Cultivo <span class="req">*</span></label>
              <input type="text" id="cultivo_nombre" name="nombre" class="af-form-input" placeholder="Ej: cafe, cacao, platano..." required />
            </div>

            <div class="af-form-group">
              <label for="cultivo_variedad">Variedad / Tipo <span class="req">*</span></label>
              <input type="text" id="cultivo_variedad" name="variedad" class="af-form-input" placeholder="Ej: Castillo, CCN-51, Hass..." required />
            </div>

            <div class="af-form-group">
              <label for="cultivo_cantidad">Cantidad Base Estimada</label>
              <input type="number" step="0.01" id="cultivo_cantidad" name="cantidad_cultivo" class="af-form-input" value="0.00" required />
            </div>

            <div class="af-form-group">
              <label for="cultivo_estado">Estado</label>
              <select id="cultivo_estado" name="estado" class="af-form-input" required>
                <option value="ACTIVO" selected>ACTIVO</option>
                <option value="INACTIVO">INACTIVO</option>
              </select>
            </div>
          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-cancel" data-af-modal-close="createCultivoModal">Cancelar</button>
          <button type="submit" class="btn-save">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <span>Guardar Cultivo</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 3. Modal: Registrar Cosecha / Producción -->
  <div class="af-modal-overlay" id="createProduccionModal">
    <div class="af-modal-card" style="max-width: 580px;">
      <div class="af-modal-header">
        <div class="af-modal-title">
          <div class="modal-title-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
          </div>
          <div>
            <h3>Registrar Cosecha / Producción</h3>
            <p>Asigna la recolección realizada por un trabajador en un lote</p>
          </div>
        </div>
        <button type="button" class="btn-close-modal" data-af-modal-close="createProduccionModal">&times;</button>
      </div>

      <form action="{{ route('producciones.store') }}" method="POST">
        @csrf
        <div class="af-modal-body">
          <div class="af-form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="af-form-group">
              <label for="prod_lote">Lote <span class="req">*</span></label>
              <select id="prod_lote" name="id_lote" class="af-form-input" required>
                <option value="" disabled selected>Selecciona un lote</option>
                @foreach ($lotes as $lote)
                  <option value="{{ $lote->id_lote }}">{{ ucfirst($lote->nombre) }} ({{ $lote->cultivo->nombre ?? 'general' }})</option>
                @endforeach
              </select>
            </div>

            <div class="af-form-group">
              <label for="prod_trabajador">Trabajador Responsable <span class="req">*</span></label>
              <select id="prod_trabajador" name="id_trabajador" class="af-form-input" required>
                <option value="" disabled selected>Selecciona trabajador</option>
                @foreach ($trabajadores as $trab)
                  <option value="{{ $trab->id_trabajador }}">{{ $trab->nombres }} {{ $trab->apellidos }} (Doc: {{ $trab->documento }})</option>
                @endforeach
              </select>
            </div>

            <div class="af-form-group">
              <label for="prod_cantidad">Cantidad Recolectada <span class="req">*</span></label>
              <input type="number" step="0.01" id="prod_cantidad" name="cantidad" class="af-form-input" placeholder="Ej: 120.50" required />
            </div>

            <div class="af-form-group">
              <label for="prod_unidad">Unidad de Medida <span class="req">*</span></label>
              <select id="prod_unidad" name="unidad_medida" class="af-form-input" required>
                <option value="kg" selected>Kilogramos (kg)</option>
                <option value="ton">Toneladas (ton)</option>
                <option value="bultos">Bultos / Sacos</option>
                <option value="arrobas">Arrobas (@)</option>
              </select>
            </div>

            <div class="af-form-group" style="grid-column: span 2;">
              <label for="prod_fecha">Fecha de Cosecha <span class="req">*</span></label>
              <input type="date" id="prod_fecha" name="fecha" class="af-form-input" value="{{ date('Y-m-d') }}" required />
            </div>
          </div>
        </div>

        <div class="af-modal-footer">
          <button type="button" class="btn-cancel" data-af-modal-close="createProduccionModal">Cancelar</button>
          <button type="submit" class="btn-save">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <span>Registrar Producción</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modales Dinámicos: Ver Detalles y Editar Lotes -->
  @foreach ($lotes as $lote)
    <!-- Modal: Ver Detalles del Lote -->
    <div class="af-modal-overlay" id="viewLoteModal_{{ $lote->id_lote }}">
      <div class="af-modal-card" style="max-width: 650px;">
        <div class="af-modal-header">
          <div class="af-modal-title">
            <div class="modal-title-icon green">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <div>
              <h3>Detalles del Lote: {{ ucfirst($lote->nombre) }}</h3>
              <p>Información detallada, cultivo asociado y registros de producción</p>
            </div>
          </div>
          <button type="button" class="btn-close-modal" data-af-modal-close="viewLoteModal_{{ $lote->id_lote }}">&times;</button>
        </div>

        <div class="af-modal-body">
          <div class="profile-info-list" style="margin-bottom: 20px;">
            <div class="profile-info-item">
              <div class="profile-info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
              </div>
              <div class="profile-info-content">
                <div class="profile-info-label">Ubicación</div>
                <div class="profile-info-val">{{ $lote->ubicacion_descripcion ?? 'Sin ubicación registrada' }}</div>
              </div>
            </div>

            <div class="profile-info-item">
              <div class="profile-info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
              </div>
              <div class="profile-info-content">
                <div class="profile-info-label">Extensión</div>
                <div class="profile-info-val">{{ $lote->formatted_extension }}</div>
              </div>
            </div>

            <div class="profile-info-item">
              <div class="profile-info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a9 9 0 0 1 9 9c0 4.97-4.03 9-9 9A9 9 0 0 1 3 11a9 9 0 0 1 9-9Z"/><path d="M12 7v10"/><path d="M8 12h8"/></svg>
              </div>
              <div class="profile-info-content">
                <div class="profile-info-label">Cultivo y Variedad</div>
                <div class="profile-info-val">
                  <span class="pill-crop">{{ $lote->cultivo->nombre ?? 'general' }}</span>
                  <span style="font-size: 13px; color: var(--text-muted); margin-left: 6px;">{{ $lote->cultivo->variedad ?? '' }}</span>
                </div>
              </div>
            </div>

            <div class="profile-info-item">
              <div class="profile-info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
              </div>
              <div class="profile-info-content">
                <div class="profile-info-label">Producción Acumulada</div>
                <div class="profile-info-val" style="font-weight: 700; color: #059669;">{{ $lote->produccion_total }}</div>
              </div>
            </div>
          </div>

          <!-- Historial de Producciones del Lote -->
          <div style="border-top: 1px solid var(--border); padding-top: 16px;">
            <h4 style="font-size: 15px; font-weight: 700; color: var(--secondary-color); margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
              <span>Historial de Cosechas en este Lote</span>
              <span style="font-size: 12px; font-weight: 500; color: var(--text-muted);">{{ $lote->producciones->count() }} registros</span>
            </h4>

            @if ($lote->producciones->count() > 0)
              <div class="af-table-responsive" style="max-height: 200px; overflow-y: auto;">
                <table class="af-table-data" style="font-size: 13px;">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Trabajador</th>
                      <th>Cantidad</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($lote->producciones as $prod)
                      <tr>
                        <td>{{ $prod->formatted_fecha }}</td>
                        <td>{{ $prod->trabajador->usuario->name ?? 'Trabajador' }}</td>
                        <td style="font-weight: 600; color: #059669;">{{ $prod->cantidad }} {{ $prod->unidad_medida }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <p style="font-size: 13px; color: var(--text-muted); background: #f8fafc; padding: 12px; border-radius: 8px; text-align: center;">
                No se han registrado cosechas en este lote aún.
              </p>
            @endif
          </div>
        </div>

        <div class="af-modal-footer" style="justify-content: space-between;">
          <form action="{{ route('lotes.destroy', $lote->id_lote) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este lote y todas sus producciones asociadas?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-cancel" style="color: #dc2626; border-color: #fecaca; background: #fef2f2;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
              <span>Eliminar Lote</span>
            </button>
          </form>

          <div style="display: flex; gap: 8px;">
            <button type="button" class="btn-outline" data-af-modal-close="viewLoteModal_{{ $lote->id_lote }}" data-af-modal-open="editLoteModal_{{ $lote->id_lote }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
              <span>Editar</span>
            </button>
            <button type="button" class="btn-cancel" data-af-modal-close="viewLoteModal_{{ $lote->id_lote }}">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal: Editar Lote -->
    <div class="af-modal-overlay" id="editLoteModal_{{ $lote->id_lote }}">
      <div class="af-modal-card" style="max-width: 580px;">
        <div class="af-modal-header">
          <div class="af-modal-title">
            <div class="modal-title-icon green">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
            </div>
            <div>
              <h3>Editar Lote: {{ $lote->nombre }}</h3>
              <p>Actualiza la ubicación, extensión o cultivo asignado</p>
            </div>
          </div>
          <button type="button" class="btn-close-modal" data-af-modal-close="editLoteModal_{{ $lote->id_lote }}">&times;</button>
        </div>

        <form action="{{ route('lotes.update', $lote->id_lote) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="af-modal-body">
            <div class="af-form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
              <div class="af-form-group">
                <label for="edit_lote_nombre_{{ $lote->id_lote }}">Nombre del Lote <span class="req">*</span></label>
                <input type="text" id="edit_lote_nombre_{{ $lote->id_lote }}" name="nombre" class="af-form-input" value="{{ $lote->nombre }}" required />
              </div>

              <div class="af-form-group">
                <label for="edit_lote_extension_{{ $lote->id_lote }}">Extensión (Hectáreas) <span class="req">*</span></label>
                <input type="number" step="0.01" id="edit_lote_extension_{{ $lote->id_lote }}" name="extension" class="af-form-input" value="{{ $lote->extension }}" required />
              </div>

              <div class="af-form-group" style="grid-column: span 2;">
                <label for="edit_lote_ubicacion_{{ $lote->id_lote }}">Ubicación / Descripción <span class="req">*</span></label>
                <input type="text" id="edit_lote_ubicacion_{{ $lote->id_lote }}" name="ubicacion_descripcion" class="af-form-input" value="{{ $lote->ubicacion_descripcion }}" required />
              </div>

              <div class="af-form-group">
                <label for="edit_lote_cultivo_{{ $lote->id_lote }}">Cultivo Principal <span class="req">*</span></label>
                <select id="edit_lote_cultivo_{{ $lote->id_lote }}" name="id_cultivo" class="af-form-input" required>
                  @foreach ($cultivos as $cultivo)
                    <option value="{{ $cultivo->id_cultivo }}" {{ $lote->id_cultivo == $cultivo->id_cultivo ? 'selected' : '' }}>
                      {{ ucfirst($cultivo->nombre) }} ({{ $cultivo->variedad }})
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="af-form-group">
                <label for="edit_lote_fecha_{{ $lote->id_lote }}">Fecha de Registro</label>
                <input type="date" id="edit_lote_fecha_{{ $lote->id_lote }}" name="fecha_registro" class="af-form-input" value="{{ $lote->fecha_registro }}" />
              </div>
            </div>
          </div>

          <div class="af-modal-footer">
            <button type="button" class="btn-cancel" data-af-modal-close="editLoteModal_{{ $lote->id_lote }}">Cancelar</button>
            <button type="submit" class="btn-save">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              <span>Guardar Cambios</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  @endforeach

</x-admin-layout>
