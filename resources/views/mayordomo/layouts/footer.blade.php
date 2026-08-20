<!-- ======================================================================
     FOOTER / PIE DE PÁGINA DEL MAYORDOMO
     ====================================================================== -->
<footer class="admin-footer">
  <div class="footer-left">
    <span>&copy; {{ date('Y') }} <strong>AgroFinca</strong> - Módulo Operativo del Mayordomo.</span>
  </div>
  <div class="footer-right">
    <span class="system-status-indicator">
      <span class="status-dot-pulse"></span>
      Sistema Operativo en Línea
    </span>
  </div>
</footer>

<!-- Scripts Globales AgroFinca -->
<script src="{{ asset('js/admin.js') }}?v=3.0"></script>
@stack('scripts')
