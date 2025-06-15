<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración básica del documento -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito de Compras</title>
    
    <!-- Framework CSS Tailwind desde CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Token CSRF de Laravel para protección de formularios -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<!-- Inclusión del banner de navegación (Blade template) -->
@include('banner')

<!-- 
    BODY PRINCIPAL
    - bg-gray-50: Fondo gris claro (#f9fafb)
    - min-h-screen: Altura mínima del 100% del viewport
-->
<body class="bg-gray-50 min-h-screen">
    <!-- 
        CONTENEDOR PRINCIPAL
        - container mx-auto: Contenedor centrado con márgenes automáticos
        - px-4: Padding horizontal de 16px
        - py-8: Padding vertical de 32px
    -->
    <div class="container mx-auto px-4 py-8">
        <!-- 
            CONTENEDOR INTERNO LIMITADO
            - max-w-6xl: Ancho máximo de 1152px (72rem)
            - mx-auto: Centrado horizontal
        -->
        <div class="max-w-6xl mx-auto">
            
            <!-- ==================== HEADER DEL CARRITO ==================== -->
            <!-- mb-8: Margen inferior de 32px -->
            <div class="mb-8">
                <!-- 
                    TÍTULO PRINCIPAL
                    - text-3xl: Tamaño de fuente 30px
                    - font-bold: Peso de fuente 700
                    - text-gray-900: Color gris muy oscuro
                    - mb-2: Margen inferior de 8px
                -->
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Mi Carrito</h1>
                <!-- 
                    SUBTÍTULO
                    - text-gray-600: Color gris medio para contraste reducido
                -->
                <p class="text-gray-600">Revisa tus productos antes de proceder al pago</p>
            </div>

            <!-- ==================== SISTEMA DE ALERTAS ==================== -->
            <!-- 
                CONTENEDOR DE ALERTAS DINÁMICAS
                - hidden: Oculto por defecto (display: none)
                - mb-6: Margen inferior de 24px
                - Controlado por JavaScript para mostrar mensajes de éxito/error
            -->
            <div id="alert-container" class="hidden mb-6">
                <!-- 
                    MENSAJE DE ALERTA
                    - px-4 py-3: Padding horizontal 16px, vertical 12px
                    - rounded: Bordes redondeados de 4px
                    - Las clases de color se asignan dinámicamente por JS
                -->
                <div id="alert-message" class="px-4 py-3 rounded"></div>
            </div>

            <!-- ==================== LAYOUT PRINCIPAL (GRID) ==================== -->
            <!-- 
                GRID RESPONSIVO
                - grid: Activar CSS Grid
                - grid-cols-1: 1 columna en móvil
                - lg:grid-cols-3: 3 columnas en pantallas grandes (1024px+)
                - gap-8: Espacio de 32px entre elementos del grid
            -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- ==================== COLUMNA IZQUIERDA: PRODUCTOS ==================== -->
                <!-- 
                    LISTA DE PRODUCTOS (ocupa 2/3 del ancho en desktop)
                    - lg:col-span-2: Ocupa 2 columnas del grid en pantallas grandes
                -->
                <div class="lg:col-span-2">
                    
                    <!-- CONDICIONAL BLADE: Si hay productos en el carrito -->
                    @if(isset($carrito) && count($carrito) > 0)
                        
                        <!-- ==================== TARJETA DE PRODUCTOS ==================== -->
                        <!-- 
                            CONTENEDOR DE PRODUCTOS
                            - bg-white: Fondo blanco
                            - rounded-lg: Bordes redondeados de 8px
                            - shadow-md: Sombra media para profundidad
                            - overflow-hidden: Oculta contenido que desborde
                        -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            
                            <!-- HEADER DE LA LISTA DE PRODUCTOS -->
                            <!-- 
                                - p-6: Padding de 24px en todos los lados
                                - border-b border-gray-200: Borde inferior gris claro
                            -->
                            <div class="p-6 border-b border-gray-200">
                                <!-- 
                                    FLEXBOX PARA ALINEAR TÍTULO Y BOTÓN
                                    - flex: Activar flexbox
                                    - justify-between: Espaciar elementos en extremos
                                    - items-center: Centrar verticalmente
                                -->
                                <div class="flex justify-between items-center">
                                    <!-- 
                                        TÍTULO CON CONTADOR DINÁMICO
                                        - text-xl: Tamaño de fuente 20px
                                        - font-semibold: Peso de fuente 600
                                    -->
                                    <h2 class="text-xl font-semibold text-gray-900">
                                        Productos (<span id="total-items">{{ count($carrito) }}</span>)
                                    </h2>
                                    
                                    <!-- FORMULARIO PARA VACIAR CARRITO -->
                                    <!-- 
                                        FORM con confirmación JavaScript
                                        - onsubmit: Ejecuta confirm() antes de enviar
                                        - @csrf: Token de protección Laravel
                                    -->
                                    <form action="{{ route('carrito.vaciar') }}" method="POST" 
                                          onsubmit="return confirm('¿Estás seguro de vaciar todo el carrito?')">
                                        @csrf
                                        <!-- 
                                            BOTÓN VACIAR
                                            - text-red-600: Color rojo base
                                            - hover:text-red-800: Color rojo más oscuro al hover
                                            - text-sm: Tamaño de fuente pequeño (14px)
                                            - font-medium: Peso de fuente 500
                                        -->
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Vaciar Carrito
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- ==================== LISTA DE PRODUCTOS ==================== -->
                            <!-- 
                                CONTENEDOR DE PRODUCTOS
                                - divide-y: Líneas divisorias horizontales entre elementos
                                - divide-gray-200: Color gris claro para las divisorias
                            -->
                            <div id="productos-container" class="divide-y divide-gray-200">
                                
                                <!-- BUCLE BLADE: Iterar sobre cada producto del carrito -->
                                @foreach($carrito as $producto)
                                    <!-- 
                                        ITEM DE PRODUCTO
                                        - p-6: Padding de 24px
                                        - flex: Layout flexbox horizontal
                                        - items-center: Centrar elementos verticalmente
                                        - space-x-4: Espacio horizontal de 16px entre hijos
                                        - data-producto-id: Atributo personalizado para identificación en JS
                                    -->
                                    <div class="p-6 flex items-center space-x-4" data-producto-id="{{ $producto['id'] }}">
                                        
                                        <!-- ==================== IMAGEN DEL PRODUCTO ==================== -->
                                        <!-- 
                                            CONTENEDOR DE IMAGEN
                                            - flex-shrink-0: No se encoge en flexbox (mantiene tamaño)
                                        -->
                                        <div class="flex-shrink-0">
                                            <!-- 
                                                LÓGICA PHP PARA IMAGEN
                                                - Imagen por defecto: placeholder.jpg
                                                - Busca imagen específica en: images/material/{imagen}/Frente.jpg
                                                - Verifica existencia del archivo antes de mostrar
                                            -->
                                            @php
                                                $imagenUrl = asset('images/placeholder.jpg');
                                                if (isset($producto['imagen']) && !empty($producto['imagen'])) {
                                                    $rutaImagen = 'images/material/' . trim($producto['imagen'], '/') . '/Frente.jpg';
                                                    if (file_exists(public_path($rutaImagen))) {
                                                        $imagenUrl = asset($rutaImagen);
                                                    }
                                                }
                                            @endphp
                                            <!-- 
                                                IMAGEN DEL PRODUCTO
                                                - w-20 h-20: Dimensiones 80x80px
                                                - object-cover: Mantiene aspecto recortando si es necesario
                                                - rounded-lg: Bordes redondeados
                                                - border: Borde sutil
                                                - onerror: Fallback a placeholder si falla la carga
                                            -->
                                            <img src="{{ $imagenUrl }}" 
                                                 alt="{{ $producto['nombre'] }}" 
                                                 class="w-20 h-20 object-cover rounded-lg border"
                                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                        </div>
                                        
                                        <!-- ==================== INFORMACIÓN DEL PRODUCTO ==================== -->
                                        <!-- 
                                            CONTENEDOR DE INFO
                                            - flex-1: Toma todo el espacio disponible
                                            - min-w-0: Permite que el contenido se encoja (para truncate)
                                        -->
                                        <div class="flex-1 min-w-0">
                                            <!-- 
                                                NOMBRE DEL PRODUCTO
                                                - text-lg: Tamaño de fuente 18px
                                                - font-medium: Peso de fuente 500
                                                - truncate: Corta texto largo con "..."
                                            -->
                                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                                {{ $producto['nombre'] }}
                                            </h3>
                                            <!-- 
                                                PRECIO UNITARIO
                                                - text-lg: Tamaño de fuente 18px
                                                - font-semibold: Peso de fuente 600
                                                - mt-2: Margen superior de 8px
                                                - Clase precio-unitario para manipulación con JS
                                            -->
                                            <p class="text-lg font-semibold text-gray-900 mt-2">
                                                €<span class="precio-unitario">{{ number_format($producto['precio'], 2) }}</span>
                                            </p>
                                        </div>
                                        
                                        <!-- ==================== CONTROLES DE CANTIDAD ==================== -->
                                        <!-- 
                                            CONTENEDOR DE CONTROLES
                                            - flex: Layout horizontal
                                            - items-center: Centrado vertical
                                            - space-x-3: Espacio de 12px entre elementos
                                        -->
                                        <div class="flex items-center space-x-3">
                                            
                                            <!-- BOTÓN DECREMENTAR -->
                                            <!-- 
                                                - w-8 h-8: Dimensiones 32x32px
                                                - rounded-full: Completamente redondeado (círculo)
                                                - bg-gray-100: Fondo gris claro
                                                - hover:bg-gray-200: Fondo más oscuro al hover
                                                - flex items-center justify-center: Centrar icono
                                                - transition duration-150: Transición suave 150ms
                                                - onclick: Llamada a función JS con parámetros
                                            -->
                                            <button type="button" 
                                                    onclick="cambiarCantidad({{ $producto['id'] }}, -1)"
                                                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition duration-150">
                                                <!-- ICONO SVG MENOS (línea horizontal) -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            
                                            <!-- INPUT DE CANTIDAD -->
                                            <!-- 
                                                - type="number": Campo numérico con controles nativos
                                                - id único: Para identificación en JavaScript
                                                - value: Cantidad actual del producto
                                                - min="1": Valor mínimo permitido
                                                - max: Limitado por stock disponible
                                                - w-16: Ancho de 64px
                                                - text-center: Texto centrado
                                                - border border-gray-300: Borde gris estándar
                                                - rounded-md: Bordes ligeramente redondeados
                                                - py-1: Padding vertical de 4px
                                                - onchange: Ejecuta función JS al cambiar valor
                                            -->
                                            <input type="number" 
                                                   id="cantidad_{{ $producto['id'] }}"
                                                   value="{{ $producto['cantidad'] }}" 
                                                   min="1" 
                                                   max="{{ $producto['stock_disponible'] ?? 99 }}"
                                                   class="w-16 text-center border border-gray-300 rounded-md py-1"
                                                   onchange="actualizarCantidad({{ $producto['id'] }}, this.value)">
                                            
                                            <!-- BOTÓN INCREMENTAR -->
                                            <!-- Clases similares al botón decrementar -->
                                            <button type="button" 
                                                    onclick="cambiarCantidad({{ $producto['id'] }}, 1)"
                                                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition duration-150">
                                                <!-- ICONO SVG MÁS (cruz) -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- ==================== SUBTOTAL Y ELIMINAR ==================== -->
                                        <!-- 
                                            COLUMNA DERECHA
                                            - text-right: Alineación de texto a la derecha
                                        -->
                                        <div class="text-right">
                                            <!-- 
                                                SUBTOTAL DEL PRODUCTO
                                                - Cálculo dinámico: precio * cantidad
                                                - Clase subtotal-producto para manipulación JS
                                            -->
                                            <p class="text-lg font-semibold text-gray-900">
                                                €<span class="subtotal-producto">{{ number_format($producto['precio'] * $producto['cantidad'], 2) }}</span>
                                            </p>
                                            
                                            <!-- BOTÓN ELIMINAR PRODUCTO -->
                                            <!-- 
                                                - text-red-600: Color rojo para acción destructiva
                                                - hover:text-red-800: Rojo más oscuro al hover
                                                - text-sm: Tamaño pequeño (14px)
                                                - mt-2: Margen superior de 8px
                                                - onclick: Función JS para eliminar producto
                                            -->
                                            <button type="button" 
                                                    onclick="eliminarProducto({{ $producto['id'] }})"
                                                    class="text-red-600 hover:text-red-800 text-sm mt-2 transition duration-150">
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                    @else
                        <!-- ==================== CARRITO VACÍO ==================== -->
                        <!-- 
                            ESTADO VACÍO
                            - bg-white: Fondo blanco
                            - rounded-lg shadow-md: Bordes redondeados y sombra
                            - p-12: Padding generoso de 48px
                            - text-center: Contenido centrado
                        -->
                        <div class="bg-white rounded-lg shadow-md p-12 text-center">
                            <!-- 
                                ICONO DE CARRITO VACÍO
                                - w-24 h-24: Dimensiones grandes 96x96px
                                - text-gray-300: Color gris claro
                                - mx-auto: Centrado horizontal
                                - mb-6: Margen inferior de 24px
                            -->
                            <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H19M17 17v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v8.01"></path>
                            </svg>
                            
                            <!-- TÍTULO ESTADO VACÍO -->
                            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Tu carrito está vacío</h2>
                            
                            <!-- DESCRIPCIÓN -->
                            <p class="text-gray-600 mb-6">Explora nuestros productos y añade algunos a tu carrito</p>
                            
                            <!-- BOTÓN CONTINUAR COMPRANDO -->
                            <!-- 
                                - inline-flex: Flexbox inline para centrar contenido
                                - items-center: Centrado vertical del contenido
                                - px-6 py-3: Padding horizontal 24px, vertical 12px
                                - bg-blue-600: Fondo azul principal
                                - text-white: Texto blanco
                                - font-medium: Peso de fuente 500
                                - rounded-lg: Bordes redondeados
                                - hover:bg-blue-700: Azul más oscuro al hover
                                - transition duration-150: Transición suave
                            -->
                            <a href="/catalogo" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-150">
                                Continuar Comprando
                            </a>
                        </div>
                    @endif
                </div>

                <!-- ==================== COLUMNA DERECHA: RESUMEN ==================== -->
                <!-- Condicional: Solo mostrar resumen si hay productos -->
                @if(isset($carrito) && count($carrito) > 0)
                    <!-- 
                        COLUMNA DE RESUMEN (1/3 del ancho en desktop)
                        - lg:col-span-1: Ocupa 1 columna del grid
                    -->
                    <div class="lg:col-span-1">
                        <!-- 
                            TARJETA DE RESUMEN
                            - bg-white: Fondo blanco
                            - rounded-lg shadow-md: Bordes redondeados y sombra
                            - p-6: Padding de 24px
                            - sticky top-6: Se mantiene fijo al hacer scroll, 24px desde arriba
                        -->
                        <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                            
                            <!-- TÍTULO DEL RESUMEN -->
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">Resumen del Pedido</h2>
                            
                            <!-- ==================== DETALLES DEL PRECIO ==================== -->
                            <!-- 
                                CONTENEDOR DE DETALLES
                                - space-y-3: Espacio vertical de 12px entre elementos hijos
                                - mb-6: Margen inferior de 24px
                            -->
                            <div class="space-y-3 mb-6">
                                
                                <!-- SUBTOTAL -->
                                <!-- 
                                    FILA SUBTOTAL
                                    - flex: Layout flexbox
                                    - justify-between: Espaciar texto y precio en extremos
                                    - text-gray-600: Color gris medio
                                -->
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <!-- Span con ID para actualización dinámica por JS -->
                                    <span>€<span id="subtotal-display">{{ number_format($totalPrecio ?? 0, 2) }}</span></span>
                                </div>
                                
                                <!-- ENVÍO -->
                                <div class="flex justify-between text-gray-600">
                                    <span>Envío</span>
                                    <!-- 
                                        LÓGICA DE ENVÍO
                                        - Gratis si subtotal >= €50
                                        - €5.99 si subtotal < €50
                                    -->
                                    <span id="envio-display">
                                        @if(($totalPrecio ?? 0) >= 50)
                                            Gratis
                                        @else
                                            €5.99
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- LÍNEA DIVISORIA -->
                                <!-- hr: Línea horizontal con borde gris claro -->
                                <hr class="border-gray-200">
                                
                                <!-- TOTAL FINAL -->
                                <!-- 
                                    FILA TOTAL
                                    - text-lg: Tamaño de fuente más grande (18px)
                                    - font-semibold: Peso de fuente 600
                                    - text-gray-900: Color gris muy oscuro (más prominente)
                                -->
                                <div class="flex justify-between text-lg font-semibold text-gray-900">
                                    <span>Total</span>
                                    <span>€<span id="total-display">
                                        <!-- 
                                            CÁLCULO PHP DEL TOTAL
                                            - Subtotal + envío (si aplica)
                                        -->
                                        @php
                                            $subtotal = $totalPrecio ?? 0;
                                            $envio = $subtotal >= 50 ? 0 : 5.99;
                                            $total = $subtotal + $envio;
                                        @endphp
                                        {{ number_format($total, 2) }}
                                    </span></span>
                                </div>
                            </div>

                            <!-- ==================== BOTONES DE ACCIÓN ==================== -->
                            <!-- 
                                CONTENEDOR DE BOTONES
                                - space-y-3: Espacio vertical de 12px entre botones
                            -->
                            <div class="space-y-3">
                                
                                <!-- BOTÓN PROCEDER AL PAGO -->
                                <!-- 
                                    BOTÓN PRINCIPAL
                                    - w-full: Ancho completo
                                    - bg-blue-600: Fondo azul principal
                                    - hover:bg-blue-700: Azul más oscuro al hover
                                    - text-white: Texto blanco
                                    - font-semibold: Peso de fuente 600
                                    - py-3 px-4: Padding vertical 12px, horizontal 16px
                                    - rounded-lg: Bordes redondeados
                                    - transition duration-150: Transición suave
                                    - onclick: Función JS para abrir modal de checkout
                                -->
                                <button type="button" 
                                        onclick="abrirModalCheckout()"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-150">
                                    Proceder al Pago
                                </button>
                                
                                <!-- BOTÓN CONTINUAR COMPRANDO -->
                                <!-- 
                                    BOTÓN SECUNDARIO
                                    - w-full: Ancho completo
                                    - bg-gray-100: Fondo gris claro
                                    - hover:bg-gray-200: Gris más oscuro al hover
                                    - text-gray-800: Texto gris oscuro
                                    - font-medium: Peso de fuente 500
                                    - text-center: Texto centrado
                                    - block: Display block para el enlace
                                -->
                                <a href="/catalogo" 
                                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 px-4 rounded-lg transition duration-150 text-center block">
                                    Continuar Comprando
                                </a>
                            </div>

                            <!-- ==================== GARANTÍAS Y BENEFICIOS ==================== -->
                            <!-- 
                                SECCIÓN DE GARANTÍAS
                                - mt-6: Margen superior de 24px
                                - pt-6: Padding superior de 24px
                                - border-t border-gray-200: Borde superior gris claro
                            -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <!-- 
                                    LISTA DE GARANTÍAS
                                    - space-y-3: Espacio vertical de 12px entre elementos
                                    - text-sm: Tamaño de fuente pequeño (14px)
                                    - text-gray-600: Color gris medio
                                -->
                                <div class="space-y-3 text-sm text-gray-600">
                                    
                                    <!-- ENVÍO GRATUITO -->
                                    <!-- 
                                        ITEM DE GARANTÍA
                                        - flex: Layout flexbox
                                        - items-center: Centrado vertical
                                    -->
                                    <div class="flex items-center">
                                        <!-- 
                                            ICONO CHECK VERDE
                                            - w-4 h-4: Dimensiones 16x16px
                                            - text-green-500: Color verde
                                            - mr-2: Margen derecho de 8px
                                        -->
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Envío gratuito desde €50
                                    </div>
                                    
                                    <!-- DEVOLUCIONES -->
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Devoluciones en 30 días
                                    </div>
                                    
                                    <!-- PAGO SEGURO -->
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Pago 100% seguro
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ==================== MODAL DE CHECKOUT ==================== -->
    <!-- 
        MODAL OVERLAY
        - fixed inset-0: Posición fija cubriendo toda la pantalla
        - bg-black bg-opacity-50: Fondo negro semi-transparente
        - hidden: Oculto por defecto
        - z-50: Z-index alto para estar encima de todo
        - flex items-center justify-center: Centrar modal en pantalla
        - p-4: Padding de 16px para evitar que toque bordes en móvil
    -->
    <div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <!-- 
            CONTENEDOR DEL MODAL
            - bg-white: Fondo blanco
            - rounded-lg: Bordes redondeados
            - shadow-xl: Sombra extra grande para profundidad
            - max-w-4xl: Ancho máximo de 896px
            - w-full: Ancho completo hasta el máximo
            - max-h-[90vh]: Altura máxima del 90% del viewport
            - overflow-y-auto: Scroll vertical si el contenido es muy alto
        -->
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            
            <!-- ==================== HEADER DEL MODAL ==================== -->
            <!-- 
                HEADER CON TÍTULO Y BOTÓN CERRAR
                - flex items-center justify-between: Flexbox con elementos en extremos
                - p-6: Padding de 24px
                - border-b border-gray-200: Borde inferior gris claro
            -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <!-- TÍTULO DEL MODAL -->
                <h2 class="text-2xl font-bold text-gray-900">Finalizar Compra</h2>
                
                <!-- BOTÓN CERRAR MODAL -->
                <!-- 
                    - text-gray-400: Color gris claro
                    - hover:text-gray-600: Gris más oscuro al hover
                    - onclick: Función JS para cerrar modal
                -->
                <button type="button" onclick="cerrarModalCheckout()" class="text-gray-400 hover:text-gray-600">
                    <!-- ICONO X PARA CERRAR -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- ==================== FORMULARIO DE CHECKOUT ==================== -->
            <!-- 
                FORMULARIO PRINCIPAL
                - id: Para identificación en JavaScript
                - onsubmit: Función JS personalizada para procesar pedido
                - class="p-6": Padding de 24px
            -->
            <form id="checkout-form" onsubmit="procesarPedido(event)" class="p-6">
                <!-- 
                    GRID DEL FORMULARIO
                    - grid grid-cols-1 lg:grid-cols-2: 1 columna en móvil, 2 en desktop
                    - gap-8: Espacio de 32px entre columnas
                -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- ==================== COLUMNA IZQUIERDA: INFORMACIÓN DE ENVÍO ==================== -->
                    <div>
                        <!-- TÍTULO SECCIÓN ENVÍO -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Envío</h3>
                        
                        <!-- 
                            CONTENEDOR DE CAMPOS
                            - space-y-4: Espacio vertical de 16px entre campos
                        -->
                        <div class="space-y-4">
                            
                            <!-- NOMBRE Y APELLIDOS (GRID 2 COLUMNAS) -->
                            <!-- 
                                - grid grid-cols-2 gap-4: 2 columnas con espacio de 16px
                            -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- CAMPO NOMBRE -->
                                <div>
                                    <!-- 
                                        LABEL
                                        - block: Display block para ocupar línea completa
                                        - text-sm: Tamaño pequeño (14px)
                                        - font-medium: Peso de fuente 500
                                        - text-gray-700: Color gris oscuro
                                        - mb-1: Margen inferior de 4px
                                        - * indica campo obligatorio
                                    -->
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                                    <!-- 
                                        INPUT NOMBRE
                                        - type="text": Campo de texto
                                        - required: Campo obligatorio HTML5
                                        - w-full: Ancho completo
                                        - border border-gray-300: Borde gris estándar
                                        - rounded-md: Bordes ligeramente redondeados
                                        - px-3 py-2: Padding horizontal 12px, vertical 8px
                                        - focus:outline-none: Quitar outline por defecto
                                        - focus:ring-2 focus:ring-blue-500: Anillo azul al hacer focus
                                    -->
                                    <input type="text" id="nombre" name="nombre" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <!-- CAMPO APELLIDOS -->
                                <div>
                                    <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">Apellidos *</label>
                                    <input type="text" id="apellidos" name="apellidos" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- CAMPO EMAIL -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <!-- type="email": Validación de email HTML5 -->
                                <input type="email" id="email" name="email" required 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- CAMPO TELÉFONO -->
                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                                <!-- type="tel": Optimizado para números de teléfono -->
                                <input type="tel" id="telefono" name="telefono" required 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- CAMPO DNI -->
                            <div>
                                <label for="dni" class="block text-sm font-medium text-gray-700 mb-1">DNI *</label>
                                <!-- 
                                    - placeholder: Texto de ejemplo
                                    - pattern y validación se pueden agregar por JS
                                -->
                                <input type="text" id="dni" name="dni" required 
                                       placeholder="12345678A"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- CAMPO DIRECCIÓN -->
                            <div>
                                <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                                <input type="text" id="direccion" name="direccion" required 
                                       placeholder="Calle, Número, Piso..."
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- CIUDAD Y CÓDIGO POSTAL (GRID 2 COLUMNAS) -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- CAMPO CIUDAD -->
                                <div>
                                    <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                                    <input type="text" id="ciudad" name="ciudad" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <!-- CAMPO CÓDIGO POSTAL -->
                                <div>
                                    <label for="codigo_postal" class="block text-sm font-medium text-gray-700 mb-1">Código Postal *</label>
                                    <!-- 
                                        - pattern="[0-9]{5}": Expresión regular para 5 dígitos
                                        - maxlength="5": Máximo 5 caracteres
                                    -->
                                    <input type="text" id="codigo_postal" name="codigo_postal" required 
                                           pattern="[0-9]{5}" maxlength="5"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== COLUMNA DERECHA: INFORMACIÓN DE PAGO ==================== -->
                    <div>
                        <!-- TÍTULO SECCIÓN PAGO -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Método de Pago</h3>
                        
                        <!-- ==================== SELECTOR DE MÉTODO DE PAGO ==================== -->
                        <!-- 
                            OPCIONES DE PAGO
                            - space-y-3: Espacio vertical de 12px entre opciones
                            - mb-6: Margen inferior de 24px
                        -->
                        <div class="space-y-3 mb-6">
                            
                            <!-- OPCIÓN TARJETA (SELECCIONADA POR DEFECTO) -->
                            <!-- 
                                LABEL CON RADIO BUTTON
                                - flex items-center: Flexbox con centrado vertical
                            -->
                            <label class="flex items-center">
                                <!-- 
                                    RADIO BUTTON
                                    - type="radio": Botón de opción
                                    - name="metodo_pago": Grupo de opciones
                                    - value="tarjeta": Valor del método
                                    - checked: Seleccionado por defecto
                                    - onchange: Función JS para cambiar formulario visible
                                    - mr-3: Margen derecho de 12px
                                    - text-blue-600: Color azul para el radio activo
                                -->
                                <input type="radio" name="metodo_pago" value="tarjeta" checked 
                                       onchange="cambiarMetodoPago(this.value)"
                                       class="mr-3 text-blue-600">
                                <span>Tarjeta de Crédito/Débito</span>
                            </label>
                            
                            <!-- OPCIÓN PAYPAL -->
                            <label class="flex items-center">
                                <input type="radio" name="metodo_pago" value="paypal" 
                                       onchange="cambiarMetodoPago(this.value)"
                                       class="mr-3 text-blue-600">
                                <span>PayPal</span>
                            </label>
                            
                            <!-- OPCIÓN TRANSFERENCIA -->
                            <label class="flex items-center">
                                <input type="radio" name="metodo_pago" value="transferencia" 
                                       onchange="cambiarMetodoPago(this.value)"
                                       class="mr-3 text-blue-600">
                                <span>Transferencia Bancaria</span>
                            </label>
                        </div>

                        <!-- ==================== FORMULARIO DE TARJETA ==================== -->
                        <!-- 
                            CAMPOS DE TARJETA (VISIBLE POR DEFECTO)
                            - id="tarjeta-form": Para manipulación con JS
                            - space-y-4: Espacio vertical entre campos
                        -->
                        <div id="tarjeta-form" class="space-y-4">
                            
                            <!-- NÚMERO DE TARJETA -->
                            <div>
                                <label for="numero_tarjeta" class="block text-sm font-medium text-gray-700 mb-1">Número de Tarjeta *</label>
                                <!-- 
                                    - placeholder: Formato ejemplo
                                    - maxlength="19": Máximo 19 caracteres (16 dígitos + 3 espacios)
                                    - Se puede agregar formateo automático con JS
                                -->
                                <input type="text" id="numero_tarjeta" name="numero_tarjeta" 
                                       placeholder="1234 5678 9012 3456" maxlength="19"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- FECHA EXPIRACIÓN Y CVV (GRID 2 COLUMNAS) -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- FECHA EXPIRACIÓN -->
                                <div>
                                    <label for="fecha_expiracion" class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                                    <!-- 
                                        - placeholder="MM/AA": Formato esperado
                                        - maxlength="5": MM/AA = 5 caracteres
                                    -->
                                    <input type="text" id="fecha_expiracion" name="fecha_expiracion" 
                                           placeholder="MM/AA" maxlength="5"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <!-- CVV -->
                                <div>
                                    <label for="cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV *</label>
                                    <!-- 
                                        - maxlength="4": 3-4 dígitos según tipo de tarjeta
                                        - type="text" para controlar entrada manualmente
                                    -->
                                    <input type="text" id="cvv" name="cvv" 
                                           placeholder="123" maxlength="4"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- TITULAR DE LA TARJETA -->
                            <div>
                                <label for="titular_tarjeta" class="block text-sm font-medium text-gray-700 mb-1">Titular *</label>
                                <input type="text" id="titular_tarjeta" name="titular_tarjeta" 
                                       placeholder="Nombre como aparece en la tarjeta"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- INFORMACIÓN PAYPAL (OCULTA POR DEFECTO) -->
                        <!-- 
                            INFORMACIÓN PAYPAL (OCULTA POR DEFECTO)
                            - hidden: Oculto inicialmente
                            - bg-yellow-50: Fondo amarillo muy claro para indicar pendiente
                            - border border-yellow-200: Borde amarillo claro
                            - rounded-md: Bordes redondeados
                            - p-4: Padding de 16px
                        -->
                        <div id="paypal-form" class="hidden bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <!-- 
                                TEXTO INFORMATIVO DE DISPONIBILIDAD
                                - text-sm: Tamaño pequeño
                                - text-yellow-800: Color amarillo oscuro para contraste
                                - font-medium: Peso de fuente medio para destacar
                            -->
                            <p class="text-sm text-yellow-800 font-medium">Disponible próximamente</p>
                            <p class="text-xs text-yellow-600 mt-1">Esta opción de pago estará habilitada en futuras actualizaciones.</p>
                        </div>

                        <!-- INFORMACIÓN TRANSFERENCIA (OCULTA POR DEFECTO) -->
                        <!-- 
                            INFORMACIÓN TRANSFERENCIA (OCULTA POR DEFECTO)
                            - bg-yellow-50: Fondo amarillo muy claro para indicar pendiente
                            - border border-yellow-200: Borde amarillo claro
                        -->
                        <div id="transferencia-form" class="hidden bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <!-- TEXTO PRINCIPAL -->
                            <p class="text-sm text-yellow-800 font-medium">Disponible próximamente</p>
                            <!-- 
                                TEXTO SECUNDARIO
                                - text-xs: Tamaño extra pequeño (12px)
                                - text-yellow-600: Color amarillo medio
                            -->
                            <p class="text-xs text-yellow-600 mt-1">Esta opción de pago estará habilitada en futuras actualizaciones.</p>
                        </div>
                    </div>
                </div>

                <!-- ==================== TÉRMINOS Y CONDICIONES ==================== -->
                <!-- 
                    CHECKBOX TÉRMINOS
                    - mt-6: Margen superior de 24px
                -->
                <div class="mt-6">
                    <!-- 
                        LABEL CON CHECKBOX
                        - flex items-start: Flexbox con alineación al inicio (para textos largos)
                    -->
                    <label class="flex items-start">
                        <!-- 
                            CHECKBOX
                            - type="checkbox": Casilla de verificación
                            - required: Campo obligatorio
                            - mt-1: Margen superior de 4px para alinear con texto
                            - mr-3: Margen derecho de 12px
                            - text-blue-600: Color azul cuando está marcado
                        -->
                        <input type="checkbox" id="terminos" name="terminos" required 
                               class="mt-1 mr-3 text-blue-600">
                        <!-- 
                            TEXTO DE TÉRMINOS
                            - text-sm: Tamaño pequeño
                            - text-gray-600: Color gris medio
                        -->
                        <span class="text-sm text-gray-600">
                            Acepto los términos y condiciones y la política de privacidad
                        </span>
                    </label>
                </div>

                <!-- ==================== BOTONES DEL MODAL ==================== -->
                <!-- 
                    CONTENEDOR DE BOTONES
                    - flex justify-end: Flexbox con alineación a la derecha
                    - space-x-3: Espacio horizontal de 12px entre botones
                    - mt-8: Margen superior de 32px
                    - pt-6: Padding superior de 24px
                    - border-t border-gray-200: Borde superior gris claro
                -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    
                    <!-- BOTÓN CANCELAR -->
                    <!-- 
                        BOTÓN SECUNDARIO
                        - px-6 py-2: Padding horizontal 24px, vertical 8px
                        - border border-gray-300: Borde gris estándar
                        - rounded-md: Bordes redondeados
                        - text-gray-700: Texto gris oscuro
                        - hover:bg-gray-50: Fondo gris claro al hover
                        - transition duration-150: Transición suave
                        - onclick: Función JS para cerrar modal
                    -->
                    <button type="button" onclick="cerrarModalCheckout()" 
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                        Cancelar
                    </button>
                    
                    <!-- BOTÓN CONFIRMAR PEDIDO -->
                    <!-- 
                        BOTÓN PRINCIPAL
                        - type="submit": Envía el formulario
                        - id: Para manipulación con JS (cambiar texto, deshabilitar)
                        - px-6 py-2: Padding horizontal 24px, vertical 8px
                        - bg-blue-600: Fondo azul principal
                        - hover:bg-blue-700: Azul más oscuro al hover
                        - text-white: Texto blanco
                        - rounded-md: Bordes redondeados
                        - transition duration-150: Transición suave
                        - disabled:opacity-50: Opacidad reducida cuando está deshabilitado
                    -->
                    <button type="submit" id="btn-procesar-pedido"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-150 disabled:opacity-50">
                        Confirmar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script>
        // ==================== VARIABLES GLOBALES ====================
        
        /**
         * Token CSRF obtenido del meta tag para protección de formularios
         * @type {string}
         * @description Utilizado en todas las peticiones POST para evitar ataques CSRF
         */
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ==================== FUNCIONES DE INTERFAZ ====================

        /**
         * Muestra alertas dinámicas en la interfaz de usuario
         * @param {string} mensaje - Texto a mostrar en la alerta
         * @param {string} [tipo='success'] - Tipo de alerta: 'success' o 'error'
         * @description 
         * - Configura colores según el tipo (verde para éxito, rojo para error)
         * - Maneja mensajes multilínea dividiéndolos en divs separados
         * - Auto-oculta la alerta después de 3s (éxito) o 5s (error)
         * - Utiliza las clases de Tailwind para estilizado dinámico
         */
        function mostrarAlerta(mensaje, tipo = 'success') {
            const container = document.getElementById('alert-container');
            const messageDiv = document.getElementById('alert-message');
            
            // Mostrar contenedor de alerta
            container.classList.remove('hidden');
            
            // Configurar estilos según tipo de alerta
            messageDiv.className = `px-4 py-3 rounded ${tipo === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'}`;
            
            // Manejar mensajes multilínea
            if (mensaje.includes('\n')) {
                messageDiv.innerHTML = mensaje.split('\n').map(line => 
                    line.trim() ? `<div>${line}</div>` : ''
                ).join('');
            } else {
                messageDiv.textContent = mensaje;
            }
            
            // Auto-ocultar alerta
            setTimeout(() => {
                container.classList.add('hidden');
            }, tipo === 'error' ? 5000 : 3000);
        }

        // ==================== FUNCIONES DE GESTIÓN DEL CARRITO ====================

        /**
         * Modifica la cantidad de un producto mediante botones +/-
         * @param {number} productoId - ID único del producto en el carrito
         * @param {number} cambio - Cantidad a sumar/restar (+1 o -1)
         * @description 
         * - Obtiene la cantidad actual del input correspondiente
         * - Valida que la nueva cantidad esté dentro de los límites (1 y stock máximo)
         * - Actualiza el input y llama a actualizarCantidad() si es válido
         * - Muestra mensaje de error si se excede el stock disponible
         */
        function cambiarCantidad(productoId, cambio) {
            const input = document.getElementById(`cantidad_${productoId}`);
            const nuevaCantidad = parseInt(input.value) + cambio;
            const maxStock = parseInt(input.getAttribute('max'));
            
            if (nuevaCantidad >= 1 && nuevaCantidad <= maxStock) {
                input.value = nuevaCantidad;
                actualizarCantidad(productoId, nuevaCantidad);
            } else if (nuevaCantidad > maxStock) {
                mostrarAlerta(`Stock máximo disponible: ${maxStock}`, 'error');
            }
        }

        /**
         * Actualiza la cantidad de un producto en el servidor via AJAX
         * @param {number} productoId - ID del producto a actualizar
         * @param {number} cantidad - Nueva cantidad deseada
         * @async
         * @description 
         * - Envía petición POST al endpoint de Laravel para actualizar carrito
         * - Incluye token CSRF para seguridad
         * - Actualiza la interfaz si la operación es exitosa
         * - Muestra mensajes de error si falla la actualización
         * - Utiliza JSON para el intercambio de datos
         */
        function actualizarCantidad(productoId, cantidad) {
            fetch('{{ route("carrito.actualizar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    producto_id: productoId,
                    cantidad: cantidad
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    actualizarInterfaz();
                    mostrarAlerta('Cantidad actualizada');
                } else {
                    mostrarAlerta(data.message || 'Error al actualizar', 'error');
                }
            })
            .catch(error => {
                mostrarAlerta('Error al actualizar cantidad', 'error');
            });
        }

        /**
         * Elimina un producto del carrito con confirmación del usuario
         * @param {number} productoId - ID del producto a eliminar
         * @async
         * @description 
         * - Solicita confirmación del usuario antes de proceder
         * - Envía petición DELETE al servidor
         * - Elimina el elemento del DOM si la operación es exitosa
         * - Actualiza totales y contadores
         * - Recarga la página si el carrito queda vacío (para mostrar estado vacío)
         */
        function eliminarProducto(productoId) {
            if (!confirm('¿Eliminar este producto del carrito?')) return;

            fetch('{{ route("carrito.eliminar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ producto_id: productoId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Eliminar elemento del DOM
                    document.querySelector(`[data-producto-id="${productoId}"]`).remove();
                    actualizarInterfaz();
                    mostrarAlerta('Producto eliminado');
                    
                    // Recargar página si carrito queda vacío
                    if (data.count === 0) {
                        setTimeout(() => window.location.reload(), 1500);
                    }
                } else {
                    mostrarAlerta(data.message || 'Error al eliminar', 'error');
                }
            });
        }

        /**
         * Actualiza todos los totales y contadores en la interfaz
         * @async
         * @description 
         * - Obtiene información actualizada del carrito desde el servidor
         * - Actualiza contador de productos
         * - Recalcula subtotal, envío y total
         * - Aplica lógica de envío gratuito (gratis si >= €50)
         * - Actualiza todos los elementos DOM correspondientes
         */
        function actualizarInterfaz() {
            fetch('{{ route("carrito.info") }}')
            .then(response => response.json())
            .then(data => {
                // Actualizar contador de productos
                document.getElementById('total-items').textContent = data.count || 0;
                document.getElementById('subtotal-display').textContent = data.total_precio;
                
                // Calcular envío y total
                const subtotal = parseFloat(data.total_precio.replace(',', ''));
                const envio = subtotal >= 50 ? 0 : 5.99;
                const total = subtotal + envio;
                
                // Actualizar elementos de interfaz
                document.getElementById('envio-display').textContent = envio === 0 ? 'Gratis' : `€${envio.toFixed(2)}`;
                document.getElementById('total-display').textContent = total.toFixed(2);
            });
        }

        // ==================== FUNCIONES DEL MODAL ====================

        /**
         * Abre el modal de checkout
         * @description 
         * - Remueve la clase 'hidden' del modal
         * - Bloquea el scroll del body para focus en modal
         * - El modal se centra automáticamente por CSS
         */
        function abrirModalCheckout() {
            document.getElementById('checkout-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        /**
         * Cierra el modal de checkout
         * @description 
         * - Añade la clase 'hidden' al modal
         * - Restaura el scroll del body
         * - Mantiene los datos del formulario intactos
         */
        function cerrarModalCheckout() {
            document.getElementById('checkout-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        /**
         * Cambia la visualización del formulario según método de pago
         * @param {string} metodo - Método seleccionado: 'tarjeta', 'paypal', 'transferencia'
         * @description 
         * - Oculta todos los formularios de pago
         * - Muestra solo el formulario correspondiente al método seleccionado
         * - Configura campos como requeridos/opcionales según el método
         * - Mantiene coherencia en la UX de pago
         */
        function cambiarMetodoPago(metodo) {
            // Ocultar todos los formularios
            document.getElementById('tarjeta-form').classList.add('hidden');
            document.getElementById('paypal-form').classList.add('hidden');
            document.getElementById('transferencia-form').classList.add('hidden');
            
            if (metodo === 'tarjeta') {
                // Mostrar formulario de tarjeta y hacer campos obligatorios
                document.getElementById('tarjeta-form').classList.remove('hidden');
                ['numero_tarjeta', 'fecha_expiracion', 'cvv', 'titular_tarjeta'].forEach(field => {
                    document.getElementById(field).required = true;
                });
            } else {
                // Para otros métodos, campos de tarjeta no son obligatorios
                ['numero_tarjeta', 'fecha_expiracion', 'cvv', 'titular_tarjeta'].forEach(field => {
                    document.getElementById(field).required = false;
                });
                
                if (metodo === 'paypal') {
                    document.getElementById('paypal-form').classList.remove('hidden');
                } else if (metodo === 'transferencia') {
                    document.getElementById('transferencia-form').classList.remove('hidden');
                }
            }
        }

        // ==================== FUNCIÓN PRINCIPAL DE PROCESAMIENTO ====================

        /**
         * Procesa el pedido final y gestiona toda la lógica de checkout
         * @param {Event} event - Evento del formulario submit
         * @async
         * @description 
         * FLUJO COMPLETO DE PROCESAMIENTO:
         * 1. Previene el envío por defecto del formulario
         * 2. Deshabilita el botón para evitar doble envío
         * 3. Recopila todos los datos del formulario
         * 4. Envía petición POST al servidor con datos JSON
         * 5. Maneja respuesta del servidor:
         *    - ÉXITO: Limpia carrito, muestra confirmación, redirige a /catalogo
         *    - ERROR DE VALIDACIÓN: Muestra errores específicos y resalta campos
         *    - ERROR GENERAL: Muestra mensaje de error genérico
         * 6. Rehabilita botón si hay errores
         * 
         * CARACTERÍSTICAS ESPECIALES:
         * - Redirección automática a /catalogo tras éxito
         * - Limpieza completa del carrito (servidor + frontend)
         * - Manejo robusto de errores con feedback visual
         * - Prevención de envíos duplicados
         */
        function procesarPedido(event) {
            event.preventDefault();

            // Deshabilitar botón y cambiar texto para feedback inmediato
            const btn = document.getElementById('btn-procesar-pedido');
            btn.disabled = true;
            btn.textContent = 'Procesando...';

            // Recopilar datos del formulario
            const formData = new FormData(event.target);
            const datos = Object.fromEntries(formData);

            // Enviar petición al servidor
            fetch('{{ route("carrito.procesar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    //PROCESAMIENTO EXITOSO
                    
                    // Limpiar carrito completamente (servidor + frontend)
                    limpiarCarritoCompleto();
                    
                    // Mostrar mensaje de confirmación
                    mostrarAlerta(`¡Pedido confirmado! Número: #${data.numero_pedido}`);
                    cerrarModalCheckout();
                    
                    //REDIRECCIÓN AUTOMÁTICA A /CATALOGO
                    setTimeout(() => {
                        window.location.href = '/catalogo';
                    }, 2000);
                    
                } else if (data.errors) {
                    //ERRORES DE VALIDACIÓN
                    
                    // Construir mensaje de errores
                    let errorMessage = 'Corrige los siguientes errores:\n';
                    Object.keys(data.errors).forEach(field => {
                        errorMessage += `• ${field}: ${data.errors[field].join(', ')}\n`;
                    });
                    mostrarAlerta(errorMessage, 'error');
                    
                    // Limpiar resaltado previo de errores
                    document.querySelectorAll('.border-red-500').forEach(el => {
                        el.classList.remove('border-red-500');
                        el.classList.add('border-gray-300');
                    });
                    
                    // Resaltar campos con errores
                    Object.keys(data.errors).forEach(field => {
                        const input = document.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('border-red-500');
                            input.classList.remove('border-gray-300');
                        }
                    });
                } else {
                    //ERROR GENERAL
                    mostrarAlerta(data.message || 'Error al procesar el pedido', 'error');
                }
                
                // Rehabilitar botón si hay errores
                if (!data.success) {
                    btn.disabled = false;
                    btn.textContent = 'Confirmar Pedido';
                }
            })
            .catch(error => {
                //ERROR DE CONEXIÓN
                console.error('Error:', error);
                mostrarAlerta('Error de conexión', 'error');
                btn.disabled = false;
                btn.textContent = 'Confirmar Pedido';
            });
        }

        // ==================== FUNCIONES DE LIMPIEZA DEL CARRITO ====================

        /**
         * Limpia completamente el carrito del usuario (servidor + frontend)
         * @async
         * @description 
         * OPERACIONES DE LIMPIEZA:
         * 1. Envía petición al servidor para vaciar carrito en sesión/DB
         * 2. Limpia localStorage y sessionStorage (si se usan)
         * 3. Actualiza interfaz inmediatamente con valores en cero
         * 
         * CASOS DE USO:
         * - Después de un pedido exitoso
         * - Funciones de administración/debugging
         * - Reset completo del estado del carrito
         */
        function limpiarCarritoCompleto() {
            // Limpiar carrito en el servidor
            fetch('{{ route("carrito.vaciar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Carrito limpiado en servidor:', data);
            })
            .catch(error => {
                console.error('Error limpiando carrito:', error);
            });

            // Limpiar storage local (por si se usa para persistencia)
            if (typeof(Storage) !== "undefined") {
                localStorage.removeItem('carrito');
                sessionStorage.removeItem('carrito');
            }

            // Actualizar interfaz inmediatamente (sin esperar servidor)
            document.getElementById('total-items').textContent = '0';
            document.getElementById('subtotal-display').textContent = '0.00';
            document.getElementById('envio-display').textContent = 'Gratis';
            document.getElementById('total-display').textContent = '0.00';
        }

        /**
         * Función de emergencia para limpiar carrito manualmente
         * @description 
         * UTILIDAD DE ADMINISTRACIÓN:
         * - Solicita confirmación explícita del usuario
         * - Ejecuta limpieza completa del carrito
         * - Recarga la página para mostrar estado actualizado
         * - Útil para debugging o casos edge
         */
        function limpiarCarritoEmergencia() {
            if (confirm('¿Limpiar completamente el carrito? Esta acción no se puede deshacer.')) {
                limpiarCarritoCompleto();
                mostrarAlerta('Carrito limpiado correctamente');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        }

        // ==================== CONFIGURACIÓN DE EVENT LISTENERS ====================

        /**
         * Configuración de todos los event listeners al cargar la página
         * @description 
         * EVENTOS CONFIGURADOS:
         * 
         * 1. AUTO-ACTUALIZACIÓN DE CANTIDADES:
         *    - Detecta cambios en inputs numéricos
         *    - Implementa debounce de 800ms para evitar spam de peticiones
         *    - Valida rango antes de enviar al servidor
         * 
         * 2. NAVEGACIÓN POR TECLADO:
         *    - Tecla Escape cierra el modal
         *    - Mejora accesibilidad y UX
         * 
         * 3. CLICK FUERA DEL MODAL:
         *    - Detecta clicks en el overlay (fondo negro)
         *    - Cierra modal si click no es en contenido
         *    - Comportamiento esperado por usuarios
         */
        document.addEventListener('DOMContentLoaded', function() {
            
            // ==================== AUTO-ACTUALIZACIÓN DE CANTIDADES ====================
            /**
             * Event listener para inputs de cantidad con debounce
             * @description 
             * - Aplica debounce de 800ms para evitar múltiples peticiones
             * - Extrae ID del producto del atributo id del input
             * - Valida que la cantidad esté en rango válido (1-99)
             * - Solo actualiza si el valor es válido
             */
            document.querySelectorAll('input[type="number"]').forEach(input => {
                let timeout;
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    const productoId = this.id.replace('cantidad_', '');
                    timeout = setTimeout(() => {
                        if (this.value >= 1 && this.value <= 99) {
                            actualizarCantidad(productoId, parseInt(this.value));
                        }
                    }, 800); // Debounce de 800ms
                });
            });

            // ==================== NAVEGACIÓN POR TECLADO ====================
            /**
             * Event listener para tecla Escape
             * @param {KeyboardEvent} e - Evento de teclado
             * @description Mejora la accesibilidad permitiendo cerrar modal con Escape
             */
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') cerrarModalCheckout();
            });

            // ==================== CLICK FUERA DEL MODAL ====================
            /**
             * Event listener para cerrar modal clickeando fuera
             * @param {MouseEvent} e - Evento de click
             * @description 
             * - Detecta si el click fue en el overlay (elemento modal)
             * - No cierra si el click fue en el contenido del modal
             * - Comportamiento UX estándar para modales
             */
            document.getElementById('checkout-modal').addEventListener('click', function(e) {
                if (e.target === this) cerrarModalCheckout();
            });
        });

        // ==================== FUNCIONES AUXILIARES Y UTILIDADES ====================

        /**
         * Formatea automáticamente número de tarjeta mientras el usuario escribe
         * @param {string} value - Valor actual del input
         * @returns {string} - Número formateado con espacios
         * @description 
         * FUNCIONALIDAD OPCIONAL (no implementada en este código pero útil):
         * - Añade espacios cada 4 dígitos
         * - Limita a 16 dígitos + 3 espacios
         * - Mejora legibilidad del número de tarjeta
         */
        function formatearNumeroTarjeta(value) {
            // Implementación opcional para formateo automático
            return value.replace(/\s/g, '').replace(/(.{4})/g, '$1 ').trim();
        }

        /**
         * Valida formato de fecha de expiración MM/AA
         * @param {string} fecha - Fecha en formato MM/AA
         * @returns {boolean} - true si es válida
         * @description 
         * VALIDACIONES:
         * - Formato correcto MM/AA
         * - Mes entre 01-12
         * - Año no anterior al actual
         */
        function validarFechaExpiracion(fecha) {
            const regex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
            if (!regex.test(fecha)) return false;
            
            const [mes, año] = fecha.split('/');
            const fechaActual = new Date();
            const añoCompleto = 2000 + parseInt(año);
            const fechaTarjeta = new Date(añoCompleto, parseInt(mes) - 1);
            
            return fechaTarjeta >= fechaActual;
        }

        /**
         * Detecta tipo de tarjeta basado en el número
         * @param {string} numero - Número de tarjeta sin espacios
         * @returns {string} - Tipo de tarjeta: 'visa', 'mastercard', 'amex', etc.
         * @description 
         * DETECCIÓN POR PREFIJOS:
         * - Visa: empieza por 4
         * - MasterCard: 5xxx o 2221-2720
         * - American Express: 34xx, 37xx
         * - Útil para mostrar iconos o aplicar validaciones específicas
         */
        function detectarTipoTarjeta(numero) {
            const num = numero.replace(/\s/g, '');
            if (/^4/.test(num)) return 'visa';
            if (/^5[1-5]/.test(num)) return 'mastercard';
            if (/^3[47]/.test(num)) return 'amex';
            return 'unknown';
        }

        // ==================== DEBUGGING Y DESARROLLO ====================

        /**
         * Función de debugging para inspeccionar estado del carrito
         * @description 
         * UTILIDAD DE DESARROLLO:
         * - Muestra información completa del carrito en consola
         * - Útil para debugging y desarrollo
         * - Solo para entornos de desarrollo
         */
        function debugCarrito() {
            fetch('{{ route("carrito.info") }}')
            .then(response => response.json())
            .then(data => {
                console.log('Estado actual del carrito:', data);
                console.log('Productos:', data.productos);
                console.log('Total:', data.total_precio);
                console.log('Cantidad de items:', data.count);
            });
        }

        /**
         * Simula proceso de pago para testing
         * @description 
         * UTILIDAD DE TESTING:
         * - Simula respuesta exitosa del servidor
         * - Útil para probar flujo completo sin procesar pago real
         * - Solo para entornos de desarrollo/testing
         */
        function simularPagoExitoso() {
            const numeroAleatorio = Math.floor(Math.random() * 10000);
            mostrarAlerta(`¡Pedido simulado confirmado! Número: #DEMO${numeroAleatorio}`);
            limpiarCarritoCompleto();
            cerrarModalCheckout();
            setTimeout(() => {
                console.log('Redirección simulada a /catalogo');
                // window.location.href = '/catalogo'; // Descomentado para testing real
            }, 2000);
        }

        // ==================== ANALYTICS Y TRACKING ====================

        /**
         * Envía eventos de analytics para tracking de e-commerce
         * @param {string} evento - Nombre del evento
         * @param {Object} datos - Datos adicionales del evento
         * @description 
         * EVENTOS COMUNES:
         * - 'add_to_cart': Producto añadido al carrito
         * - 'remove_from_cart': Producto eliminado del carrito
         * - 'begin_checkout': Inicio del proceso de checkout
         * - 'purchase': Compra completada
         * - Integración con Google Analytics, Facebook Pixel, etc.
         */
        function trackearEvento(evento, datos = {}) {
            // Integración con Google Analytics 4
            if (typeof gtag !== 'undefined') {
                gtag('event', evento, datos);
            }
            
            // Integración con Facebook Pixel
            if (typeof fbq !== 'undefined') {
                fbq('track', evento, datos);
            }
            
            // Console log para debugging
            console.log('Evento trackeado:', evento, datos);
        }  
    </script>
</body>
</html>