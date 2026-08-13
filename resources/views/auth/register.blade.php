<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AgroFinca - Solicitud de Registro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/registre.css') }}" />
</head>
<body>
  
  <div class="page-background" style="background-image: url('{{ asset('img/hero_bg.png') }}');">
    <div class="bg-overlay"></div>
  </div>

  <!-- Contenedor principal de la página -->
  <main class="pagina-registro">

    <!-- Tarjeta principal del formulario -->
    <section class="tarjeta-registro">
      
      <!-- Encabezado interior -->
      <div class="hero-registro">
        <a href="{{ url('/') }}" class="logo-vector">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20 16 4"/><path d="M7 20 12 4"/><path d="M3 20 8 4"/><path d="M22 20H2"/><path d="M14 16h6"/></svg>
        </a>
        <h1 class="titulo-principal">Solicitud de Registro</h1>
        <p class="subtitulo">
          Completa el formulario para solicitar acceso como trabajador
        </p>
      </div>

      <!-- Mensajes de Error de validación -->
      @if ($errors->any())
        <div class="alert-box alert-error">
          <div class="alert-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
          </div>
          <span>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
          </span>
        </div>
      @endif

      <form class="formulario-registro" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Sección de datos personales -->
        <div class="seccion-formulario">
          <div class="seccion-header">
            <span class="icono-seccion">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <h3 class="subtitulo-seccion">Datos Personales</h3>
          </div>

          <div class="grid-formulario">
            <div class="grupo-campo">
              <label for="nombres">Nombres <span class="req">*</span></label>
              <input type="text" id="nombres" name="nombres" placeholder="Tus nombres" value="{{ old('nombres') }}" required autofocus />
            </div>

            <div class="grupo-campo">
              <label for="apellidos">Apellidos <span class="req">*</span></label>
              <input type="text" id="apellidos" name="apellidos" placeholder="Tus apellidos" value="{{ old('apellidos') }}" required />
            </div>

            <div class="grupo-campo">
              <label for="documento">Documento <span class="req">*</span></label>
              <input type="text" id="documento" name="documento" placeholder="Ej: 1012345678" value="{{ old('documento') }}" required />
            </div>

            <div class="grupo-campo">
              <label for="telefono">Teléfono <span class="req">*</span></label>
              <input type="text" id="telefono" name="telefono" placeholder="Ej: 300 123 4567" value="{{ old('telefono') }}" required />
            </div>

            <div class="grupo-campo">
              <label for="eps">EPS <span class="req">*</span></label>
              <input type="text" id="eps" name="eps" placeholder="Ej: Sanitas, Sura..." value="{{ old('eps') }}" required />
            </div>

            <div class="grupo-campo">
              <label for="RH">Tipo de Sangre (RH) <span class="req">*</span></label>
              <input type="text" id="RH" name="RH" placeholder="Ej: O+, A-" value="{{ old('RH') }}" required />
            </div>
          </div>
        </div>

        <hr class="separador" />

        <!-- Sección de credenciales -->
        <div class="seccion-formulario">
          <div class="seccion-header">
            <span class="icono-seccion">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <h3 class="subtitulo-seccion">Credenciales de Acceso</h3>
          </div>

          <div class="grid-formulario">
            <div class="grupo-campo grupo-ancho-completo">
              <label for="username">Nombre de Usuario <span class="req">*</span></label>
              <input type="text" id="username" name="username" placeholder="Crea un nombre de usuario" value="{{ old('username') }}" required />
            </div>

            <div class="grupo-campo">
              <label for="password">Contraseña <span class="req">*</span></label>
              <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="new-password" />
            </div>

            <div class="grupo-campo">
              <label for="password_confirmation">Confirmar Contraseña <span class="req">*</span></label>
              <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password" />
            </div>
          </div>
        </div>

        <!-- Alerta info -->
        <div class="info-alert">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
          Tu solicitud quedará pendiente de revisión y aprobación por el administrador (mayordomo).
        </div>

        <!-- Botones inferiores -->
        <div class="acciones-formulario">
          <a href="{{ route('login') }}" class="btn btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver al login
          </a>
          <button type="submit" class="btn btn-verde">
            Enviar Solicitud
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
          </button>
        </div>

      </form>
    </section>
  </main>
</body>
</html>
