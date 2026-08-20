<!-- ======================================================================
     FOOTER Y SCRIPTS DEL PANEL ADMINISTRATIVO AGROFINCA
     ====================================================================== -->
<footer class="admin-footer" style="padding: 16px 28px; font-size: 13px; color: var(--text-muted); border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
  <div>© {{ date('Y') }} <strong>AgroFinca</strong>. Sistema Inteligente de Gestión Agrícola.</div>
  <div>Versión 2.0 • Logística de Campo</div>
</footer>

<!-- Scripts Globales de Interacción -->
<script src="{{ asset('js/admin.js') }}?v=3.0"></script>
@stack('scripts')
