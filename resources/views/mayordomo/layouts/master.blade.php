<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? 'Panel del Mayordomo' }} - AgroFinca</title>
  <meta name="description" content="Panel Operativo del Mayordomo - AgroFinca." />

  <!-- Google Fonts Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

  <!-- Estilos AgroFinca -->
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=3.2" />
  @stack('styles')
</head>
<body style="background: var(--bg-color, #f8fafc); min-height: 100vh; display: flex; flex-direction: column;">

  <!-- Header Superior Autónomo (Sin Sidebar) -->
  @include('mayordomo.layouts.header')

  <!-- Contenedor Principal Centrado -->
  <main class="admin-page-container" style="flex: 1; max-width: 1320px; margin: 0 auto; width: 100%; padding: 28px 24px;">
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
  @include('mayordomo.layouts.footer')

</body>
</html>
