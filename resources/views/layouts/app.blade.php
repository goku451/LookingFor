<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Looking For - Plataforma de objetos perdidos y encontrados">
    <title>@yield('title', 'Looking For')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Iconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    {{-- ═══ HEADER / NAV ═══ --}}
    <header class="header" id="mainHeader">
        <a href="{{ url('/') }}" class="header__logo">
            <img src="{{ asset('img/logo.png') }}" alt="Looking For">
        </a>

        <button class="header__toggle" id="navToggle" aria-label="Menú">
            <i class="fas fa-bars"></i>
        </button>

        <nav class="header__nav" id="mainNav">
            <ul class="nav__list">
                {{-- NAV AUTENTICADO (oculto por defecto, JS lo muestra) --}}
                <li class="nav-auth" style="display:none"><a href="{{ url('/dashboard') }}" class="nav__link @yield('nav-dashboard')">
                    <i class="fas fa-home"></i> Inicio</a></li>
                <li class="nav-auth" style="display:none"><a href="{{ url('/objetos') }}" class="nav__link @yield('nav-objetos')">
                    <i class="fas fa-box-open"></i> Objetos Perdidos</a></li>
                <li class="nav-auth" style="display:none"><a href="{{ url('/reportes') }}" class="nav__link @yield('nav-reportes')">
                    <i class="fas fa-clipboard-list"></i> Reportes</a></li>
                <li class="nav-auth" style="display:none"><a href="{{ url('/publicaciones') }}" class="nav__link @yield('nav-publicaciones')">
                    <i class="fas fa-comments"></i> Publicaciones</a></li>
                <li class="nav-auth" style="display:none"><a href="{{ url('/servicios') }}" class="nav__link @yield('nav-servicios')">
                    <i class="fas fa-concierge-bell"></i> Servicios</a></li>

                {{-- Admin dropdown (solo admins) --}}
                <li class="nav-admin nav__dropdown" style="display:none">
                    <span class="nav__link nav__link--dropdown @yield('nav-admin')">
                        <i class="fas fa-shield-alt"></i> Admin <i class="fas fa-chevron-down"></i>
                    </span>
                    <ul class="dropdown__menu">
                        <li><a href="{{ url('/admin/dashboard') }}"><i class="fas fa-chart-bar"></i> Dashboard</a></li>
                        <li><a href="{{ url('/admin/usuarios') }}"><i class="fas fa-users"></i> Usuarios</a></li>
                        <li><a href="{{ url('/admin/objetos') }}"><i class="fas fa-box"></i> Objetos</a></li>
                        <li><a href="{{ url('/admin/reportes') }}"><i class="fas fa-file-alt"></i> Reportes</a></li>
                        <li><a href="{{ url('/admin/publicaciones') }}"><i class="fas fa-comment-dots"></i> Publicaciones</a></li>
                    </ul>
                </li>

                {{-- User dropdown --}}
                <li class="nav-auth nav__dropdown" style="display:none">
                    <span class="nav__link nav__link--user">
                        <i class="fas fa-user-circle"></i>
                        <span id="navUserName">Usuario</span>
                        <i class="fas fa-chevron-down"></i>
                    </span>
                    <ul class="dropdown__menu">
                        <li><a href="{{ url('/perfil') }}"><i class="fas fa-id-card"></i> Mi Perfil</a></li>
                        <li><a href="#" id="btnLogout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>

                {{-- NAV PÚBLICO (visible por defecto) --}}
                <li class="nav-guest"><a href="{{ url('/') }}" class="nav__link @yield('nav-inicio')">Inicio</a></li>
                <li class="nav-guest"><a href="{{ url('/servicios') }}" class="nav__link @yield('nav-servicios')">
                    <i class="fas fa-concierge-bell"></i> Servicios</a></li>
                <li class="nav-guest"><a href="{{ url('/login') }}" class="nav__link @yield('nav-login')">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a></li>
                <li class="nav-guest"><a href="{{ url('/register') }}" class="nav__link nav__link--cta @yield('nav-register')">
                    <i class="fas fa-user-plus"></i> Registrarse</a></li>
            </ul>
        </nav>
    </header>

    {{-- ═══ ALERTAS GLOBALES ═══ --}}
    <div class="alerts" id="alertsContainer"></div>

    {{-- ═══ CONTENIDO ═══ --}}
    <main class="main">
        @yield('content')
    </main>

    {{-- ═══ FOOTER ═══ --}}
    <footer class="footer">
        <div class="footer__grid">
            <div class="footer__brand">
                <h3>LOOKING FOR</h3>
                <p>Confía en nosotros para recuperar tus pertenencias.</p>
            </div>
            <div class="footer__links">
                <h4>Navegación</h4>
                <a href="{{ url('/') }}">Inicio</a>
                <a href="{{ url('/login') }}">Iniciar Sesión</a>
            </div>
            <div class="footer__links">
                <h4>Servicios</h4>
                <a href="{{ url('/servicios') }}">Nuestros Servicios</a>
                <a href="{{ url('/objetos') }}">Reportar Objeto Perdido</a>
                <a href="{{ url('/reportes') }}">Reportar Objeto Encontrado</a>
            </div>
            <div class="footer__social">
                <h4>Síguenos</h4>
                <div class="social__icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-x-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            <p>&copy; {{ date('Y') }} Looking For — Todos los derechos reservados.</p>
        </div>
    </footer>

    {{-- ═══ MODAL GENÉRICO ═══ --}}
    <div class="modal" id="globalModal">
        <div class="modal__overlay" data-close-modal></div>
        <div class="modal__content">
            <button class="modal__close" data-close-modal><i class="fas fa-times"></i></button>
            <div class="modal__header">
                <h2 id="modalTitle"></h2>
            </div>
            <div class="modal__body" id="modalBody"></div>
        </div>
    </div>

    {{-- ═══ LOADING ═══ --}}
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Cargando...</p>
        </div>
    </div>

    {{-- jQuery + JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
