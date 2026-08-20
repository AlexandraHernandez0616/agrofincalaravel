<x-mayordomo-layout title="Dashboard Mayordomo - AgroFinca">
  <!-- Cabecera de Página -->
  <x-slot name="header">
    <h1>Dashboard Operativo</h1>
    <p>Panel de control consolidado con los indicadores clave de la finca en tiempo real</p>
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
       GRILLA COMPLETA DE TARJETAS KPI (3 x 3)
       ====================================================================== -->
  <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; width: 100%;">

    <!-- 1. Trabajadores Activos -->
    <div class="af-table-card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #10b981; transition: transform 0.2s ease, box-shadow 0.2s ease;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div>
          <span style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Personal</span>
          <h3 style="font-size: 17px; font-weight: 700; color: var(--secondary-color); margin: 2px 0 0 0;">Trabajadores Activos</h3>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 20px;">
          👷
        </div>
      </div>
      <div style="font-size: 38px; font-weight: 800; color: #047857; line-height: 1; font-family: 'Outfit', sans-serif;">
        {{ $trabajadoresActivos }}
      </div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border);">
        Personal activo registrado en el sistema
      </div>
    </div>

    <!-- 2. Trabajadores en Labor -->
    <div class="af-table-card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #3b82f6; transition: transform 0.2s ease, box-shadow 0.2s ease;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div>
          <span style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Operatividad</span>
          <h3 style="font-size: 17px; font-weight: 700; color: var(--secondary-color); margin: 2px 0 0 0;">Trabajadores en Labor</h3>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59, 130, 246, 0.12); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 20px;">
          🌾
        </div>
      </div>
      <div style="font-size: 38px; font-weight: 800; color: #1d4ed8; line-height: 1; font-family: 'Outfit', sans-serif;">
        {{ $trabajadoresEnLabor }}
      </div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border);">
        Colaboradores actualmente asignados a labores
      </div>
    </div>

    <!-- 3. Asistencia Marcada Hoy -->
    <div class="af-table-card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #059669; transition: transform 0.2s ease, box-shadow 0.2s ease;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div>
          <span style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Control Diario</span>
          <h3 style="font-size: 17px; font-weight: 700; color: var(--secondary-color); margin: 2px 0 0 0;">Asistencia Marcada Hoy</h3>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(5, 150, 105, 0.12); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 20px;">
          ⏱️
        </div>
      </div>
      <div style="font-size: 38px; font-weight: 800; color: #059669; line-height: 1; font-family: 'Outfit', sans-serif;">
        {{ $asistenciaHoy }}
      </div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border);">
        Registros de entrada completados en la jornada
      </div>
    </div>

    <!-- 4. Solicitudes Pendientes -->
    <div class="af-table-card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #f59e0b; transition: transform 0.2s ease, box-shadow 0.2s ease;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div>
          <span style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nuevos Registros</span>
          <h3 style="font-size: 17px; font-weight: 700; color: var(--secondary-color); margin: 2px 0 0 0;">Solicitudes Pendientes</h3>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 20px;">
          📋
        </div>
      </div>
      <div style="font-size: 38px; font-weight: 800; color: #b45309; line-height: 1; font-family: 'Outfit', sans-serif;">
        {{ $solicitudesPendientes }}
      </div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border);">
        Solicitudes de registro a la espera de aprobación
      </div>
    </div>

    <!-- 5. Tareas Pendientes -->
    <div class="af-table-card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #eab308; transition: transform 0.2s ease, box-shadow 0.2s ease;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div>
          <span style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Labores</span>
          <h3 style="font-size: 17px; font-weight: 700; color: var(--secondary-color); margin: 2px 0 0 0;">Tareas Pendientes</h3>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(234, 179, 8, 0.12); color: #ca8a04; display: flex; align-items: center; justify-content: center; font-size: 20px;">
          ⏳
        </div>
      </div>
      <div style="font-size: 38px; font-weight: 800; color: #a16207; line-height: 1; font-family: 'Outfit', sans-serif;">
        {{ $tareasPendientes }}
      </div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border);">
        Labores asignadas pendientes por iniciar
      </div>
    </div>

    <!-- 6. Tareas en Progreso -->
    <div class="af-table-card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #06b6d4; transition: transform 0.2s ease, box-shadow 0.2s ease;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div>
          <span style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Ejecución</span>
          <h3 style="font-size: 17px; font-weight: 700; color: var(--secondary-color); margin: 2px 0 0 0;">Tareas en Progreso</h3>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(6, 182, 212, 0.12); color: #06b6d4; display: flex; align-items: center; justify-content: center; font-size: 20px;">
          ✅
        </div>
      </div>
      <div style="font-size: 38px; font-weight: 800; color: #0e7490; line-height: 1; font-family: 'Outfit', sans-serif;">
        {{ $tareasEnProgreso }}
      </div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border);">
        Actividades agrícolas en ejecución activa
      </div>
    </div>

    <!-- 7. Préstamos Pendientes -->
    <div class="af-table-card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #8b5cf6; transition: transform 0.2s ease, box-shadow 0.2s ease;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div>
          <span style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Bodega</span>
          <h3 style="font-size: 17px; font-weight: 700; color: var(--secondary-color); margin: 2px 0 0 0;">Préstamos Pendientes</h3>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(139, 92, 246, 0.12); color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 20px;">
          🔑
        </div>
      </div>
      <div style="font-size: 38px; font-weight: 800; color: #6d28d9; line-height: 1; font-family: 'Outfit', sans-serif;">
        {{ $prestamosPendientes }}
      </div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border);">
        Herramientas pendientes por entrega o devolución
      </div>
    </div>

    <!-- 8. Producción del Día (kg) -->
    <div class="af-table-card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #10b981; transition: transform 0.2s ease, box-shadow 0.2s ease;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div>
          <span style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Cosecha</span>
          <h3 style="font-size: 17px; font-weight: 700; color: var(--secondary-color); margin: 2px 0 0 0;">Producción del Día</h3>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 20px;">
          📈
        </div>
      </div>
      <div style="font-size: 38px; font-weight: 800; color: #166534; line-height: 1; font-family: 'Outfit', sans-serif;">
        {{ number_format($produccionHoy, 2) }} <span style="font-size: 18px; font-weight: 600; color: var(--text-muted);">kg</span>
      </div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border);">
        Total de kilos recolectados en la jornada de hoy
      </div>
    </div>

    <!-- 9. Permiso de Liquidaciones Delegadas -->
    <div class="af-table-card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid {{ $permisoActivo ? '#10b981' : '#cbd5e1' }}; transition: transform 0.2s ease, box-shadow 0.2s ease;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div>
          <span style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Autorizaciones</span>
          <h3 style="font-size: 17px; font-weight: 700; color: var(--secondary-color); margin: 2px 0 0 0;">Liquidación Delegada</h3>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: {{ $permisoActivo ? 'rgba(16, 185, 129, 0.12)' : 'rgba(148, 163, 184, 0.15)' }}; color: {{ $permisoActivo ? '#10b981' : '#64748b' }}; display: flex; align-items: center; justify-content: center; font-size: 20px;">
          🔐
        </div>
      </div>
      <div style="font-size: 22px; font-weight: 800; color: {{ $permisoActivo ? '#166534' : '#64748b' }}; font-family: 'Outfit', sans-serif;">
        @if ($permisoActivo)
          🟢 ACTIVA
        @else
          ⚪ Sin Permiso
        @endif
      </div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border);">
        @if ($permisoActivo)
          Vigente hasta el {{ $permisoActivo->formatted_fecha_fin }}
        @else
          No tienes autorización temporal delegada
        @endif
      </div>
    </div>

  </div>

</x-mayordomo-layout>
