<x-admin-layout title="Panel de Control">
    <x-slot name="header">
        <h1>Panel de Control</h1>
        <p>Bienvenido al sistema, <strong>{{ Auth::user()->name ?? 'Administrador' }}</strong>. Resumen operativo de la finca para hoy.</p>
    </x-slot>

    <x-slot name="actions">
        <button type="button" class="btn-outline" onclick="window.location.reload();">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
            <span>Actualizar</span>
        </button>
    </x-slot>

    <!-- Métricas / KPIs AgroFinca -->
    <div class="kpi-grid">
        <!-- Cultivos Activos -->
        <div class="kpi-card">
            <div class="kpi-icon-wrap green">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a9 9 0 0 1 9 9c0 4.97-4.03 9-9 9A9 9 0 0 1 3 11a9 9 0 0 1 9-9Z"/><path d="M12 7v10"/><path d="M8 12h8"/></svg>
            </div>
            <div class="kpi-info-box">
                <div class="kpi-label">Cultivos Activos</div>
                <div class="kpi-number">12 <span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">Lotes</span></div>
                <div class="kpi-trend-note">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                    <span>100% en producción</span>
                </div>
            </div>
        </div>

        <!-- Personal en Campo -->
        <div class="kpi-card">
            <div class="kpi-icon-wrap blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="kpi-info-box">
                <div class="kpi-label">Personal Activo</div>
                <div class="kpi-number">28 <span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">Jornaleros</span></div>
                <div class="kpi-trend-note">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Asistencia completa</span>
                </div>
            </div>
        </div>

        <!-- Tareas Pendientes -->
        <div class="kpi-card">
            <div class="kpi-icon-wrap amber">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
            </div>
            <div class="kpi-info-box">
                <div class="kpi-label">Tareas de Hoy</div>
                <div class="kpi-number">08 <span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">Asignadas</span></div>
                <div class="kpi-trend-note" style="color: var(--text-muted);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>5 en curso, 3 listas</span>
                </div>
            </div>
        </div>

        <!-- Rendimiento de Cosecha -->
        <div class="kpi-card">
            <div class="kpi-icon-wrap purple">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
            </div>
            <div class="kpi-info-box">
                <div class="kpi-label">Producción Mes</div>
                <div class="kpi-number">4.8 <span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">Ton</span></div>
                <div class="kpi-trend-note">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                    <span>+12% vs mes anterior</span>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
