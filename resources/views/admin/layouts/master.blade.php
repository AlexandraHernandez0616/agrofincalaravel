<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? 'Panel de Administración' }} - AgroFinca</title>
  <meta name="description" content="Panel Administrativo de AgroFinca - Sistema Inteligente de Gestión Agrícola." />

  <!-- Google Fonts Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

  <!-- Estilos AgroFinca Admin -->
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=3.0" />
  @stack('styles')
</head>
<body>

  <!-- Overlay para móviles al desplegar menú -->
  <div class="admin-backdrop-overlay" id="sidebarOverlay"></div>

  <div class="admin-layout">

    <!-- Menú Lateral / Sidebar -->
    @include('admin.layouts.sidebar')

    <!-- Área de Contenido Principal -->
    <div class="admin-content-area">
      <!-- Header Superior -->
      @include('admin.layouts.header')

      <!-- Contenedor Dinámico de la Página -->
      <main class="admin-page-container">
        @isset($header)
          <div class="admin-page-header">
            <div>
              {{ $header }}
            </div>
            @isset($actions)
              <div class="admin-page-actions">
                {{ $actions }}
              </div>
            @endisset
          </div>
        @endisset

        {{ $slot ?? '' }}
        @yield('content')
      </main>

      <!-- Footer & Scripts -->
      @include('admin.layouts.footer')
    </div>

  </div>

</body>
</html>
