<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Padelinging</title>
    <style>
        /* ========================================
           RESET Y ESTILOS BASE
           ======================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* ========================================
           BANNER PRINCIPAL (NAVBAR)
           ======================================== */
        .banner {
            background-color: #2a9d8f;  /* Color verde azulado principal */
            color: white;
            padding: 12px 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);  /* Sombra sutil */
            position: relative;
        }
        
        /* Contenedor principal para centrar contenido */
        .banner .container {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;  /* Logo a la izquierda, menú a la derecha */
            padding: 0 15px;
        }
        
        /* ========================================
           SECCIÓN DEL LOGO
           ======================================== */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;  /* Espacio entre imagen y texto */
            text-decoration: none;
            color: white;
        }
        
        /* Efecto hover del logo */
        .logo-container:hover {
            opacity: 0.9;
            transform: scale(1.02);  /* Ligero aumento de tamaño */
            transition: all 0.3s ease;
        }
        
        /* Imagen del logo */
        .logo-image {
            width: 60px;
            height: 60px;
            object-fit: contain;  /* Mantiene proporción */
            border-radius: 6px;   /* Bordes ligeramente redondeados */
        }
        
        /* Texto del brand */
        .logo-text {
            font-weight: 700;
            font-size: 2.25em;
            color: white;
            text-decoration: none;
        }
        
        /* ========================================
           MENÚ DE NAVEGACIÓN
           ======================================== */
        .banner .menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 25px;  /* Espaciado entre elementos del menú */
            align-items: center;
        }
        
        /* Enlaces del menú */
        .banner .menu li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
            padding: 8px 12px;
            border-radius: 4px;
        }
        
        /* Efecto hover en enlaces del menú */
        .banner .menu li a:hover {
            background-color: rgba(255, 255, 255, 0.1);  /* Fondo semi-transparente */
            color: #f0f8ff;  /* Color azul claro */
        }
        
        /* ========================================
           CARRITO DE COMPRAS
           ======================================== */
        .cart-menu-item {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: transform 0.2s;
            position: relative;  /* Para posicionar el badge absolutamente */
            padding: 8px;
            border-radius: 4px;
        }
        
        /* Efecto hover del carrito */
        .cart-menu-item:hover {
            transform: scale(1.1);  /* Aumenta ligeramente el tamaño */
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Icono del carrito */
        .cart-menu-item img {
            width: 24px;
            height: 24px;
            filter: brightness(0) invert(1); /* Convierte el icono a blanco */
        }
        
        /* Badge contador de productos en carrito */
        .cart-count {
            background-color: #e63946;  /* Rojo para llamar la atención */
            color: white;
            border-radius: 50%;  /* Círculo perfecto */
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            position: absolute;  /* Posición absoluta respecto al carrito */
            top: -2px;
            right: -2px;
        }
        
        /* ========================================
           RESPONSIVE DESIGN - TABLET/MÓVIL
           ======================================== */
        @media (max-width: 768px) {
            .banner .container {
                flex-direction: column;  /* Apila logo y menú verticalmente */
                gap: 15px;
                padding: 15px;
            }
            
            .banner .menu {
                flex-wrap: wrap;  /* Permite que el menú se ajuste en varias líneas */
                justify-content: center;
                gap: 15px;
            }
            
            /* Reduce tamaño del logo en móvil */
            .logo-text {
                font-size: 1.3em;
            }
            
            .logo-image {
                width: 35px;
                height: 35px;
            }
        }
        
        /* ========================================
           RESPONSIVE DESIGN - MÓVIL PEQUEÑO
           ======================================== */
        @media (max-width: 480px) {
            .banner .menu {
                gap: 10px;  /* Menos espacio entre elementos */
            }
            
            /* Enlaces más compactos en móviles pequeños */
            .banner .menu li a {
                padding: 6px 8px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <!-- ========================================
         NAVBAR PRINCIPAL
         ======================================== -->
    <nav class="banner">
        <div class="container">
            <!-- SECCIÓN DEL LOGO -->
            <!-- Usa helper asset() de Laravel para la ruta de la imagen -->
            <a href="/" class="logo-container">
                <img class="logo-image" src="{{ asset('images/Logo.png') }}" alt="Logo Padelinging">
                <span class="logo-text">Padelinging</span>
            </a>
            
            <!-- MENÚ DE NAVEGACIÓN PRINCIPAL -->
            <ul class="menu">
                <!-- Enlaces principales de navegación -->
                <li><a href="{{ url('/') }}">Inicio</a></li>
                <li><a href="{{ url('/catalogo') }}">Tienda</a></li>
                <li><a href="{{ url('/calendario') }}">Reservas</a></li>
                
                <!-- CARRITO DE COMPRAS -->
                <li>
                    {{-- Lógica PHP para contar productos en el carrito --}}
                    @php
                        // Obtiene el carrito de la sesión y cuenta los productos
                        $cartCount = session('cart') ? count(session('cart')) : 0;
                    @endphp
                    
                    <!-- Contenedor del carrito con JavaScript inline para navegación -->
                    <div class="cart-menu-item" onclick="navigateToCart()">
                        <img src="/images/iconos/carrito_sin_fondo.png" alt="Carrito">
                        {{-- Muestra badge solo si hay productos en el carrito --}}
                        @if($cartCount > 0)
                            <span class="cart-count">{{ $cartCount }}</span>
                        @endif
                    </div>
                </li>
                
                <!-- SISTEMA DE AUTENTICACIÓN -->
                {{-- Directiva Blade: muestra contenido diferente según estado de autenticación --}}
                @guest
                    {{-- Usuario NO autenticado --}}
                    <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                @else
                    {{-- Usuario SÍ autenticado --}}
                    <!-- Enlace a historial de pedidos del usuario -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mis-pedidos') }}">
                            Mis Pedidos
                        </a>
                    </li>
                    
                    <!-- Botón de logout con nombre del usuario -->
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="handleLogout(event)">
                           Salir ({{ Auth::user()->Nombre }})
                        </a>
                    </li>
                    
                    {{-- Formulario oculto para logout seguro con token CSRF --}}
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf {{-- Token de seguridad Laravel --}}
                    </form>
                @endguest
            </ul>
        </div>
    </nav>

    <script>
        /**
         * Navega a la página del carrito
         * @function navigateToCart
         */
        function navigateToCart() {
            window.location.href = '{{ url('/carrito') }}';
        }

        /**
         * Maneja el evento de logout
         * Previene la navegación por defecto y envía el formulario de logout
         * @function handleLogout
         * @param {Event} event - Evento del click en el enlace de logout
         */
        function handleLogout(event) {
            // Previene que el enlace navegue normalmente
            event.preventDefault();
            
            // Envía el formulario de logout de forma segura
            document.getElementById('logout-form').submit();
        }
    </script>
</body>
</html>