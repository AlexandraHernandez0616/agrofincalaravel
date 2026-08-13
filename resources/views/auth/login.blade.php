<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AgroFinca - Inicio de Sesión</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
</head>
<body>
  <div class="split-layout">
    <!-- Lado Izquierdo: Imagen -->
    <div class="split-image" style="background-image: url('{{ asset('img/slider2_bg.png') }}');">
      <div class="image-overlay"></div>
      <div class="image-content">
        <a href="{{ url('/') }}" class="back-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
          Volver al inicio
        </a>
        <div class="brand-info">
          <div class="logo-vector">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20 16 4"/><path d="M7 20 12 4"/><path d="M3 20 8 4"/><path d="M22 20H2"/><path d="M14 16h6"/></svg>
          </div>
          <h1>Agro<span>Finca</span></h1>
          <p>El sistema líder en logística y gestión agrícola inteligente. Ingresa para continuar descubriendo el futuro del campo.</p>
        </div>
      </div>
    </div>

    <!-- Lado Derecho: Formulario -->
    <div class="split-form">
      <div class="form-container">
        <div class="form-header">
          <h2>Bienvenido de nuevo</h2>
          <p>Por favor, ingresa tus credenciales para acceder a tu panel.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

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

        <form class="login-form" method="POST" action="{{ route('login') }}">
          @csrf

          <div class="form-group">
            <label for="username">Usuario</label>
            <div class="input-wrapper">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              <input type="text" id="username" name="username" placeholder="Ingresa tu usuario" :value="old('username')" required autofocus autocomplete="username" />
            </div>
          </div>

          <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-wrapper">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password" />
            </div>
          </div>

          <div class="form-actions">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
            @endif
          </div>

          <!-- Remember me (Hidden but available if you want to show it later) -->
          <input type="hidden" name="remember" value="on">

          <button type="submit" class="btn btn--primary">
            Iniciar sesión
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:8px"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
          </button>

          <div class="divider">
            <span>o si eres nuevo</span>
          </div>

          @if (Route::has('register'))
          <button type="button" class="btn btn--secondary" onclick="window.location.href='{{ route('register') }}'">
            Solicitar acceso como trabajador
          </button>
          @endif
        </form>
        
        <div class="mobile-back">
          <a href="{{ url('/') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver a AgroFinca
          </a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
