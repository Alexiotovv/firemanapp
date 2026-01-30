<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SISBOM-XI CD LORETO')</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-red: #dc3545;
            --dark-red: #c82333;
            --accent-orange: #fd7e14;
            --dark-bg: #212529;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        .navbar-bomberos {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Sidebar responsive */
        .sidebar {
            background-color: var(--dark-bg);
            color: white;
            height: 100%;
            transition: all 0.3s;
        }
        
        /* Sidebar oculto en móviles por defecto */
        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                left: -250px;
                top: 56px;
                bottom: 0;
                width: 250px;
                z-index: 1040;
                overflow-y: auto;
                transition: left 0.3s;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            /* Overlay para fondo oscuro */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 56px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1039;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            /* Botón hamburguesa en navbar para móvil */
            .navbar-toggler {
                border-color: rgba(255,255,255,0.5);
            }
        }
        
        /* En tablets y escritorio, sidebar visible */
        @media (min-width: 768px) {
            .sidebar {
                min-height: calc(100vh - 56px);
                position: sticky;
                top: 56px;
            }
        }
        
        .sidebar a {
            color: #ddd;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .sidebar a:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left: 3px solid var(--accent-orange);
        }
        
        .sidebar a.active {
            color: white;
            background-color: rgba(220, 53, 69, 0.2);
            border-left: 3px solid var(--primary-red);
        }
        
        .card-bomberos {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-top: 4px solid var(--primary-red);
            transition: transform 0.3s;
        }
        
        .card-bomberos:hover {
            transform: translateY(-5px);
        }
        
        .btn-bomberos {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
            color: white;
        }
        
        .btn-bomberos:hover {
            background-color: var(--dark-red);
            border-color: var(--dark-red);
            color: white;
        }
        
        .stats-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
        }
        
        .stats-card.users {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }
        
        .stats-card.companies {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        }
        
        .table th {
            background-color: var(--light-bg);
            color: var(--dark-bg);
            font-weight: 600;
        }
        
        /* Ajuste para el contenido principal en móvil */
        @media (max-width: 767.98px) {
            .main-content {
                width: 100%;
                padding: 1rem !important;
            }
        }
    </style>
    
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark navbar-bomberos">
        <div class="container-fluid">
            <!-- Botón hamburguesa solo en móvil -->
            <button class="navbar-toggler d-md-none me-2" type="button" id="sidebarToggleMobile">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-fire me-2"></i>
                <strong>SISBOM-XI CD LORETO</strong>
            </a>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle text-white" role="button" 
                       data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Overlay para móvil -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Alertas -->
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12">
                @include('components.alert')
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar" id="sidebar">
                    <div class="text-center py-4">
                        <h5><i class="bi bi-fire me-2"></i>Menú Principal</h5>
                        @if(Auth::user()->is_admin)
                            <span class="badge bg-danger">Administrador</span>
                        @else
                            <span class="badge bg-primary">Usuario</span>
                        @endif
                    </div>
                    
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i>Gestión de Usuarios
                    </a>
                    
                    <a href="{{ route('partes-incendios.index') }}" class="{{ request()->routeIs('partes-incendios.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text me-2"></i>Partes de Incendios
                    </a>
                    
                    <a href="{{ route('partes-emergencias.index') }}" class="{{ request()->routeIs('partes-emergencias.*') ? 'active' : '' }}">
                        <i class="bi bi-ambulance me-2"></i>Emergencias Médicas
                    </a>
                    
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-bar-graph me-2"></i>Reportes
                        </a>
                        
                        <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <i class="bi bi-sliders me-2"></i>Configuración
                        </a>
                    @endif
                    
                    <a href="{{ route('users.show', Auth::user()) }}" class="{{ request()->routeIs('users.show') && request()->route('user') == Auth::id() ? 'active' : '' }}">
                        <i class="bi bi-person-circle me-2"></i>Mi Perfil
                    </a>
                    
                    <div class="mt-4 px-3">
                        <small class="text-muted">SISTEMA</small>
                    </div>
                    
                    <a href="#">
                        <i class="bi bi-question-circle me-2"></i>Ayuda
                    </a>
                </div>
            </div>
            
            <!-- Contenido principal -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                @yield('content')
            </div>
        </div>
    </div>
    @endauth
    
    @guest
        @yield('content')
    @endguest

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $(document).ready(function() {
        const sidebar = $('#sidebar');
        const sidebarOverlay = $('#sidebarOverlay');
        const sidebarToggleMobile = $('#sidebarToggleMobile');
        
        // Función para alternar sidebar en móvil
        function toggleSidebar() {
            if ($(window).width() < 768) {
                sidebar.toggleClass('show');
                sidebarOverlay.toggleClass('show');
            }
        }
        
        // Eventos para abrir/cerrar sidebar
        sidebarToggleMobile.on('click', toggleSidebar);
        sidebarOverlay.on('click', toggleSidebar);
        
        // Cerrar sidebar al hacer clic en un enlace (solo en móvil)
        if ($(window).width() < 768) {
            $('.sidebar a').on('click', toggleSidebar);
        }
        
        // Cerrar sidebar con tecla Escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.hasClass('show')) {
                toggleSidebar();
            }
        });
        
        // Ajustar dinámicamente en resize
        $(window).on('resize', function() {
            if ($(window).width() >= 768) {
                sidebar.removeClass('show');
                sidebarOverlay.removeClass('show');
            }
        });
        
        // Auto-ocultar alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
    </script>
    
    @yield('scripts')
</body>
</html>