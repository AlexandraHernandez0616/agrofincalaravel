<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? 'Panel Operativo del Mayordomo' }} - AgroFinca</title>
  <meta name="description" content="Panel Operativo del Mayordomo - Sistema Inteligente de Gestión Agrícola AgroFinca." />

  <!-- Google Fonts Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

  <!-- Estilos AgroFinca Admin / Mayordomo -->
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=3.3" />
  @stack('styles')
</head>
<body>

  <!-- Overlay para móviles al desplegar menú -->
  <div class="admin-backdrop-overlay" id="sidebarOverlay"></div>

  <div class="admin-layout">

    <!-- Menú Lateral / Sidebar del Mayordomo -->
    @include('mayordomo.layouts.sidebar')

    <!-- Área de Contenido Principal -->
    <div class="admin-content-area">
      <!-- Header Superior -->
      @include('mayordomo.layouts.header')

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

      <!-- Footer & Scripts Globales -->
      @include('mayordomo.layouts.footer')
    </div>

  </div>

</body>
</html>
