<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Padelinging</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        .banner {
            background-color: #2a9d8f;
            color: white;
            padding: 12px 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            position: relative;
        }
        
        .banner .container {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
        }
        
        /* Logo mejorado */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: white;
        }
        
        .logo-container:hover {
            opacity: 0.9;
            transform: scale(1.02);
            transition: all 0.3s ease;
        }
        
        .logo-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 6px;
        }
        
        .logo-text {
            font-weight: 700;
            font-size: 2.25em;
            color: white;
            text-decoration: none;
        }
        
        .banner .menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 25px;
            align-items: center;
        }
        
        .banner .menu li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
            padding: 8px 12px;
            border-radius: 4px;
        }
        
        .banner .menu li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #f0f8ff;
        }
        
        /* Estilos del carrito en el menú */
        .cart-menu-item {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: transform 0.2s;
            position: relative;
            padding: 8px;
            border-radius: 4px;
        }
        
        .cart-menu-item:hover {
            transform: scale(1.1);
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .cart-menu-item img {
            width: 24px;
            height: 24px;
            filter: brightness(0) invert(1); /* Hace el icono blanco */
        }
        
        .cart-count {
            background-color: #e63946;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            position: absolute;
            top: -2px;
            right: -2px;
        }
        
        @media (max-width: 768px) {
            .banner .container {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }
            
            .banner .menu {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }
            
            .logo-text {
                font-size: 1.3em;
            }
            
            .logo-image {
                width: 35px;
                height: 35px;
            }
        }
        
        @media (max-width: 480px) {
            .banner .menu {
                gap: 10px;
            }
            
            .banner .menu li a {
                padding: 6px 8px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <nav class="banner">
        <div class="container">
            <!-- Logo mejorado -->
            <a href="/" class="logo-container">
                <img class="logo-image" src="{{ asset('images/Logo.png') }}" alt="Logo Padelinging">
                <span class="logo-text">Padelinging</span>
            </a>
            
            <ul class="menu">
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="{{ url('/catalogo') }}">Tienda</a></li>
            <li><a href="{{ url('/calendario') }}">Reservas</a></li>
            <li>
                @php
                    $cartCount = session('cart') ? count(session('cart')) : 0;
                @endphp
                
                <div class="cart-menu-item" onclick="window.location.href='{{ url('/carrito') }}'">
                    <img src="/images/iconos/carrito_sin_fondo.png" alt="Carrito">
                    @if($cartCount > 0)
                        <span class="cart-count">{{ $cartCount }}</span>
                    @endif
                </div>
            </li>
            @guest
                <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('mis-pedidos') }}">
                        Mis Pedidos
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                       Salir ({{ Auth::user()->Nombre }})
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            @endguest
        </div>
    </nav>
</body>
</html>