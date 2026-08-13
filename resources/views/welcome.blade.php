<!--
  ============================================================
  ARCHIVO: resources/views/welcome.blade.php
  PROPÓSITO: Página de inicio pública (landing page)
  ============================================================
-->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AgroFinca - Sistema de Gestión Agrícola</title>
  <meta name="description" content="Plataforma líder en transformación digital y logística para la gestión moderna de fincas, cultivos y recursos tecnológicos." />
  
  <link rel="stylesheet" href="{{ asset('css/index.css') }}?v=2.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>

  <!-- Header -->
  <header class="header">
    <div class="container nav">
      <a href="{{ url('/') }}" class="logo">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20 16 4"/><path d="M7 20 12 4"/><path d="M3 20 8 4"/><path d="M22 20H2"/><path d="M14 16h6"/></svg>
        <span>AgroFinca</span>
      </a>
      <nav class="menu">
        <a href="#">Inicio</a>
        <a href="#proposito">Nuestro Propósito</a>
        <a href="#">Servicios</a>
        <a href="{{ url('/login') }}" class="nav-btn">Ingresar</a>
      </nav>
    </div>
  </header>

  <!-- Hero Slider -->
  <section id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="false">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    
    <div class="carousel-inner">
      <!-- Slide 1 -->
      <div class="carousel-item active" style="background-image: url('{{ asset('img/hero_bg.png') }}');">
        <div class="carousel-overlay"></div>
        <div class="container hero-content text-start">
          <span class="badge">🚀 El futuro del campo está aquí</span>
          <h1 class="animate-up">Gestiona tu Finca <br> de manera <span>Inteligente</span></h1>
          <p class="animate-up delay-1">
            Descubre las mejores herramientas y soluciones tecnológicas 
            aplicadas para maximizar el rendimiento de tus cosechas.
          </p>
          <a href="{{ url('/login') }}" class="btn-primary animate-up delay-2">
            Empezar Ahora
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 8px;"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
          </a>
        </div>
      </div>
      
      <!-- Slide 2 -->
      <div class="carousel-item" style="background-image: url('{{ asset('img/slider2_bg.png') }}');">
        <div class="carousel-overlay"></div>
        <div class="container hero-content text-start">
          <span class="badge">📊 Datos en tiempo real</span>
          <h1 class="animate-up">Trazabilidad Total <br> de tus <span>Cultivos</span></h1>
          <p class="animate-up delay-1">
            Lleva un registro preciso de cada etapa, desde la siembra hasta la cosecha, con nuestras herramientas avanzadas.
          </p>
          <a href="{{ url('/login') }}" class="btn-primary animate-up delay-2">
            Ver Módulos
          </a>
        </div>
      </div>
      
      <!-- Slide 3 -->
      <div class="carousel-item" style="background-image: url('{{ asset('img/slider3_bg.png') }}');">
        <div class="carousel-overlay"></div>
        <div class="container hero-content text-start">
          <span class="badge">🌱 Amigables con el planeta</span>
          <h1 class="animate-up">Agricultura Sostenible <br> y <span>Eficiente</span></h1>
          <p class="animate-up delay-1">
            Optimiza recursos como el agua y los fertilizantes con tecnología de precisión y protege el medio ambiente.
          </p>
          <a href="{{ url('/login') }}" class="btn-primary animate-up delay-2">
            Únete Hoy
          </a>
        </div>
      </div>
    </div>
    
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
  </section>

  <!-- Propósito -->
  <section id="proposito" class="purpose">
    <div class="container">
      <div class="section-header">
        <h2>Nuestro Propósito</h2>
        <p>
          Conoce los pilares que nos impulsan a transformar la agricultura mediante tecnología e innovación.
        </p>
      </div>

      <div class="cards">
        <!-- Misión -->
        <div class="card">
          <div class="img-wrapper">
            <img src="{{ asset('img/mision.png') }}" alt="Misión de AgroFinca" />
          </div>
          <div class="card-body">
            <h3>Misión</h3>
            <p>
              Proveer herramientas tecnológicas innovadoras que optimicen la gestión, trazabilidad y rentabilidad de los proyectos agrícolas.
            </p>
          </div>
        </div>

        <!-- Visión -->
        <div class="card">
          <div class="img-wrapper">
            <img src="{{ asset('img/vision.png') }}" alt="Visión de AgroFinca" />
          </div>
          <div class="card-body">
            <h3>Visión</h3>
            <p>
              Ser la plataforma líder a nivel global en la transformación digital y sostenible del sector agroindustrial.
            </p>
          </div>
        </div>

        <!-- Objetivos -->
        <div class="card">
          <div class="img-wrapper">
            <img src="{{ asset('img/objetivo.png') }}" alt="Objetivos de AgroFinca" />
          </div>
          <div class="card-body">
            <h3>Objetivos</h3>
            <p>
              Facilitar la toma de decisiones, reducir costos operativos y promover prácticas agrícolas de precisión y 100% sostenibles.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container footer-grid">
      <div class="footer-col">
        <h3><span>Agro</span>Finca</h3>
        <p>
          Plataforma integral de logística agrícola para la gestión moderna de fincas, cultivos, herramientas
          y recursos tecnológicos avanzados.
        </p>
        <div class="socials">
          <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></span>
          <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg></span>
          <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg></span>
        </div>
      </div>

      <div class="footer-col">
        <h4>Enlaces Rápidos</h4>
        <a href="#">Inicio</a>
        <a href="#proposito">Nuestro Propósito</a>
        <a href="#">Módulos del Sistema</a>
        <a href="{{ url('/login') }}">Acceso a Usuarios</a>
      </div>

      <div class="footer-col">
        <h4>Legal</h4>
        <a href="#">Términos de Servicio</a>
        <a href="#">Política de Privacidad</a>
        <a href="#">Política de Cookies</a>
      </div>

      <div class="footer-col">
        <h4>Contacto</h4>
        <p>📍 Carrera 29 A BIS #12-20<br>NEIVA - HUILA</p>
        <p>📞 +57 314 4679705</p>
        <p>✉ contacto@agrofinca.com</p>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container footer-bottom-content">
        <p>© 2026 AgroFinca. Todos los derechos reservados.</p>
        <p>Diseñado con ❤️ para el campo</p>
      </div>
    </div>
  </footer>

  <!-- Chatbot -->
  <div class="chatbot-container" id="chatbotContainer">
    <!-- Chat Header -->
    <div class="chat-header" id="chatHeader">
      <div class="chat-header-info">
        <div class="chat-avatar">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
        </div>
        <div>
          <h5>Soporte AgroFinca</h5>
          <span>En línea</span>
        </div>
      </div>
      <button class="close-chat" id="closeChatBtn">&times;</button>
    </div>
    
    <!-- Chat Body -->
    <div class="chat-body" id="chatBody">
      <div class="message bot">
        <div class="msg-content">
          ¡Hola! 👋 Soy el asistente virtual de AgroFinca. ¿En qué te puedo ayudar hoy?
        </div>
      </div>
    </div>
    
    <!-- Chat Input -->
    <div class="chat-input-area">
      <input type="text" id="chatInput" placeholder="Escribe un mensaje..." />
      <button id="sendMsgBtn">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
      </button>
    </div>
  </div>

  <!-- Botón Flotante Chat -->
  <button class="chat-fab" id="chatFabBtn">
    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
  </button>

  <!-- Script del Chatbot Interactivo -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const chatFabBtn = document.getElementById('chatFabBtn');
      const chatbotContainer = document.getElementById('chatbotContainer');
      const closeChatBtn = document.getElementById('closeChatBtn');
      const sendMsgBtn = document.getElementById('sendMsgBtn');
      const chatInput = document.getElementById('chatInput');
      const chatBody = document.getElementById('chatBody');

      // Abrir chat
      chatFabBtn.addEventListener('click', () => {
        chatbotContainer.classList.add('active');
        chatFabBtn.classList.add('hidden');
      });

      // Cerrar chat
      closeChatBtn.addEventListener('click', () => {
        chatbotContainer.classList.remove('active');
        chatFabBtn.classList.remove('hidden');
      });

      // Enviar mensaje
      const sendMessage = () => {
        const text = chatInput.value.trim();
        if(text === '') return;

        // Añadir mensaje de usuario
        const userMsg = document.createElement('div');
        userMsg.className = 'message user';
        userMsg.innerHTML = `<div class="msg-content">${text}</div>`;
        chatBody.appendChild(userMsg);
        
        chatInput.value = '';
        chatBody.scrollTop = chatBody.scrollHeight;

        // Simular respuesta del bot
        setTimeout(() => {
          const botMsg = document.createElement('div');
          botMsg.className = 'message bot';
          botMsg.innerHTML = `<div class="msg-content">Gracias por tu mensaje. Un asesor especializado se contactará contigo pronto. Si quieres, puedes dejar tu correo.</div>`;
          chatBody.appendChild(botMsg);
          chatBody.scrollTop = chatBody.scrollHeight;
        }, 1000);
      };

      sendMsgBtn.addEventListener('click', sendMessage);
      chatInput.addEventListener('keypress', (e) => {
        if(e.key === 'Enter') sendMessage();
      });
    });
  </script>

</body>
</html>
