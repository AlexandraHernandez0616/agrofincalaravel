<!-- ======================================================================
     FOOTER / PIE DE PÁGINA DEL TRABAJADOR
     ====================================================================== -->
<footer class="admin-footer">
  <div class="footer-left">
    <span>&copy; {{ date('Y') }} <strong>AgroFinca</strong> - Portal del Colaborador.</span>
  </div>
  <div class="footer-right">
    <span class="system-status-indicator">
      <span class="status-dot-pulse"></span>
      Conectado al Sistema
    </span>
  </div>
</footer>

<!-- Scripts Globales AgroFinca -->
<script src="{{ asset('js/admin.js') }}?v=3.0"></script>
@stack('scripts')
