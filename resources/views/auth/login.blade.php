<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bomberos</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
        }
        
        .login-container {
            display: flex;
            height: 100vh;
        }
        
        /* Lado izquierdo - Imagen (70%) */
        .login-image-side {
            flex: 7; /* 70% */
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        .login-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.1), rgba(0,0,0,0.05));
        }
        
        .image-content {
            position: absolute;
            bottom: 50px;
            left: 50px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .image-content h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .image-content p {
            font-size: 1.2rem;
            max-width: 500px;
        }
        
        /* Lado derecho - Formulario (30%) */
        .login-form-side {
            flex: 3; /* 30% */
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            box-shadow: -5px 0 20px rgba(0,0,0,0.1);
            z-index: 1;
        }
        
        .login-card {
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
        }
        
        .login-header h3 {
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .login-header p {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        
        .form-check-input:checked {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-image-side {
                flex: 5;
                height: 40vh;
            }
            
            .login-form-side {
                flex: 5;
                height: 60vh;
            }
            
            .image-content {
                bottom: 20px;
                left: 20px;
            }
            
            .image-content h1 {
                font-size: 1.8rem;
            }
            
            .image-content p {
                font-size: 1rem;
                max-width: 300px;
            }
        }
        
        @media (max-width: 576px) {
            .login-form-side {
                padding: 20px;
            }
            
            .image-content {
                display: none;
            }
        }
        
        /* Animación de entrada */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-card {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Lado izquierdo - Imagen (70%) -->
        <div class="login-image-side">
            <!-- Aquí va tu imagen PNG -->
            <img src="{{ asset('img/fondo_left3.png') }}" alt="Bomberos en acción" class="login-image" >
            <div class="image-overlay"></div>
            
            <div class="image-content">
                <h1>Cuerpo General de Bomberos</h1>
                <p>Servicio, honor y sacrificio al servicio de la comunidad las 24 horas del día, los 365 días del año.</p>
            </div>
        </div>
        
        <!-- Lado derecho - Formulario (30%) -->
        <div class="login-form-side">
            <div class="login-card">
                <div class="login-header">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Bomberos">
                    <h3>SISBOM-XI CD LORETO</h3>
                    <p>Acceso al sistema de gestión</p>
                </div>
                
                <div class="login-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Credenciales incorrectas
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" required autofocus
                                   placeholder="usuario@bomberos.com">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required
                                   placeholder="••••••••">
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Recordar mis datos</label>
                        </div>
                        
                        <button type="submit" class="btn btn-login mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Iniciar Sesión
                        </button>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                Sistema de Gestión Interna<br>
                                Compañía de Bomberos Voluntarios
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <script>
        // Script para manejar errores de imagen
        document.addEventListener('DOMContentLoaded', function() {
            const loginImage = document.querySelector('.login-image');
            
            // Si la imagen no carga, usar una de placeholder
            if (loginImage) {
                loginImage.onerror = function() {
                    this.src = 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80';
                };
            }
            
            // Auto-ocultar alertas después de 5 segundos
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Efecto de focus en inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });
        });
    </script>
</body>
</html>