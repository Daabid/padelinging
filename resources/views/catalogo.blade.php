<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Catálogo de Productos</title>
    <!-- Token CSRF para protección contra ataques CSRF en formularios -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Framework CSS Tailwind para estilos utilitarios -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* === ESTILOS PARA NOTIFICACIONES === */
        /* Notificaciones que aparecen desde la derecha con animación slide */
        .notification {
            transform: translateX(100%); /* Oculta inicialmente fuera de pantalla */
            transition: transform 0.3s ease; /* Animación suave de 0.3s */
        }
        .notification.show {
            transform: translateX(0); /* Muestra la notificación */
        }
        
        /* === ESTILOS PARA ICONOS DEL CARRITO === */
        /* Efecto hover en los iconos del carrito con escalado */
        .cartIcon {
            transition: transform 0.2s ease;
        }
        .cartIcon:hover {
            transform: scale(1.2); /* Agranda el icono al hacer hover */
        }
        /* Desactiva el hover cuando el botón está deshabilitado */
        .btn-carrito:disabled .cartIcon:hover {
            transform: none;
        }
        
        /* === ESTILOS PARA SLIDERS DE PRECIO === */
        /* Personaliza la apariencia de los sliders de rango de precio */
        .price-slider {
            -webkit-appearance: none;
            appearance: none;
            height: 6px;
            border-radius: 3px;
            background: #e5e7eb; /* Color gris claro */
            outline: none;
        }
        
        /* Estilo del thumb (control deslizante) para WebKit */
        .price-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #0d9488; /* Color teal */
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        /* Estilo del thumb para Firefox */
        .price-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #0d9488;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        /* === ANIMACIONES PARA PRODUCTOS === */
        /* Transiciones suaves para mostrar/ocultar productos */
        .producto-item {
            transition: all 0.3s ease;
        }
        
        .producto-item.hidden {
            opacity: 0;
            transform: scale(0.8); /* Reduce el tamaño cuando se oculta */
            display: none;
        }
        
        /* === ESTILOS PARA CONTENEDOR DE FILTROS === */
        /* Gradiente de fondo y borde para el panel de filtros */
        .filters-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 1px solid #e2e8f0;
        }

        /* === ESTILOS PARA PAGINACIÓN === */
        /* Contenedor principal de la paginación */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        /* Botones de paginación */
        .pagination-btn {
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
            min-width: 2.5rem;
            text-align: center;
        }

        /* Efecto hover para botones de paginación */
        .pagination-btn:hover:not(:disabled) {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        /* Estilo para el botón de página activa */
        .pagination-btn.active {
            background: #0d9488;
            color: white;
            border-color: #0d9488;
        }

        /* Estilo para botones deshabilitados */
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Información de paginación */
        .pagination-info {
            color: #6b7280;
            font-size: 0.875rem;
        }
    </style>
</head>

<!-- Incluye el banner del sitio (componente Laravel Blade) -->
@include('banner')

<body class="font-sans m-0 p-0 bg-gray-50">
    <!-- === TÍTULO PRINCIPAL === -->
    <h1 class="text-center mb-8 mt-8 mx-5 text-3xl font-bold text-gray-800">
        Catálogo de Productos en Venta
    </h1>
    
    <!-- === PANEL DE FILTROS === -->
    <!-- Contenedor con gradiente de fondo para los controles de filtrado -->
    <div class="filters-container max-w-6xl mx-auto px-5 mb-8 p-6 rounded-lg shadow-sm">
        <!-- Grid responsivo: 1 columna en móvil, 2 en tablet, 3 en desktop -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- === BUSCADOR DE PRODUCTOS === -->
            <div class="flex flex-col">
                <label for="searchInput" class="text-sm font-semibold text-gray-700 mb-2">
                    Buscar productos
                </label>
                <div class="relative">
                    <!-- Input de búsqueda con icono de lupa -->
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Buscar por nombre o descripción..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                    />
                    <!-- Icono SVG de lupa posicionado absolutamente -->
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- === FILTRO DE RANGO DE PRECIOS === -->
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-gray-700 mb-2">Rango de precios</label>
                <div class="space-y-3">
                    <!-- Muestra los valores mínimo y máximo seleccionados -->
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Mínimo: <span id="minPriceDisplay">0</span>€</span>
                        <span>Máximo: <span id="maxPriceDisplay">1000</span>€</span>
                    </div>
                    <!-- Dos sliders para precio mínimo y máximo -->
                    <div class="flex space-x-3">
                        <input 
                            type="range" 
                            id="minPrice" 
                            min="0" 
                            max="1000" 
                            value="0" 
                            class="price-slider flex-1"
                        />
                        <input 
                            type="range" 
                            id="maxPrice" 
                            min="0" 
                            max="1000" 
                            value="1000" 
                            class="price-slider flex-1"
                        />
                    </div>
                </div>
            </div>

            <!-- === CONTROLES DE FILTRO === -->
            <div class="flex flex-col justify-end">
                <div class="flex space-x-3">
                    <!-- Botón para limpiar todos los filtros -->
                    <button 
                        id="resetFilters" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium"
                    >
                        Limpiar filtros
                    </button>
                    <!-- Contador de productos encontrados -->
                    <div id="resultsCount" class="flex items-center px-4 py-2 bg-teal-50 text-teal-700 rounded-lg font-medium">
                        <span id="countNumber">0</span> productos
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- === CONTENIDO PRINCIPAL === -->
    <!-- Condicional Laravel Blade: si no hay productos, muestra mensaje -->
    @if($productos->isEmpty())
        <p class="text-center text-gray-600 px-5 mb-16">No hay productos disponibles en venta.</p>
    @else
        <!-- === GRID DE PRODUCTOS === -->
        <!-- Grid responsivo: 1 columna en móvil, 2 en tablet, 4 en desktop -->
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 max-w-6xl mx-auto px-5">
            <!-- Loop Laravel Blade para cada producto -->
            @foreach($productos as $producto)
                <!-- === TARJETA DE PRODUCTO === -->
                <!-- Cada producto tiene atributos data-* para el filtrado JavaScript -->
                <div class="producto-item bg-white border border-gray-300 rounded-lg p-4 shadow-md flex flex-col justify-between transition-shadow duration-300 hover:shadow-lg"
                     data-nombre="{{ strtolower($producto->Nombre) }}"
                     data-descripcion="{{ strtolower($producto->Descripcion ?? '') }}"
                     data-precio="{{ $producto->Precio }}">
                    
                    <!-- Enlace a la página de detalle del producto -->
                    <a href="{{ route('producto.show', $producto->IDProducto) }}">
                        <!-- Imagen del producto o placeholder -->
                        @if($producto->URL)
                        <img src="{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}" 
                             alt="{{ $producto->Nombre }}" 
                             class="w-full h-38 object-contain mb-4 bg-gray-50 rounded-md" />
                        @else
                        <div class="w-full h-38 flex items-center justify-center text-gray-400 bg-gray-50 rounded-md mb-4">
                            Sin imagen
                        </div>
                        @endif
                    </a>
                    
                    <!-- Nombre del producto -->
                    <div class="font-bold text-lg mb-3 text-gray-800 text-center">{{ $producto->Nombre }}</div>
                    
                    <!-- Descripción del producto (opcional) -->
                    @if($producto->Descripcion)
                    <div class="text-sm text-gray-600 mb-3 text-center line-clamp-2">{{ $producto->Descripcion }}</div>
                    @endif
                    
                    <!-- === PRECIO Y BOTÓN DE CARRITO === -->
                    <div class="flex justify-center items-center mt-3 gap-32 sm:gap-16 lg:gap-32">
                        <!-- Precio del producto -->
                        <div class="text-center">
                            <div class="font-bold text-teal-600 text-lg">{{ number_format($producto->Precio, 2) }}€</div>
                        </div>
                        <!-- Botón para añadir al carrito -->
                        <button type="button" 
                                class="btn-carrito bg-transparent border-none p-0 relative cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" 
                                data-producto-id="{{ $producto->IDProducto }}"
                                data-stock="{{ $producto->Stock ?? 0 }}"
                                {{ ($producto->Stock ?? 0) <= 0 ? 'disabled' : '' }}>
                            <img class="cartIcon h-5 w-5" src="{{ asset('images/iconos/carrito.png') }}" alt="Añadir al carrito" />
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- === SISTEMA DE PAGINACIÓN === -->
        <div id="paginationContainer" class="max-w-6xl mx-auto px-5 mt-12 mb-16">
            <div class="flex flex-col items-center space-y-4">
                <!-- Controles de paginación -->
                <div class="pagination-container">
                    <!-- Botón página anterior -->
                    <button id="prevPage" class="pagination-btn" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Contenedor para números de página (generado dinámicamente) -->
                    <div id="pageNumbers" class="flex gap-1"></div>
                    
                    <!-- Botón página siguiente -->
                    <button id="nextPage" class="pagination-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Información de paginación -->
                <div class="pagination-info text-center">
                    Página <span id="currentPageInfo">1</span> de <span id="totalPagesInfo">1</span>
                </div>
            </div>
        </div>
        
        <!-- === MENSAJE SIN RESULTADOS === -->
        <!-- Se muestra cuando los filtros no encuentran productos -->
        <div id="noResults" class="hidden text-center py-12 mb-16">
            <div class="max-w-md mx-auto">
                <!-- Icono SVG de documento -->
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron productos</h3>
                <p class="text-gray-500">Intenta ajustar los filtros o buscar con otros términos.</p>
            </div>
        </div>
    @endif

    <script>
        /**
         * === VARIABLES GLOBALES ===
         * @description Variables para manejar el estado de la aplicación
         */
        
        /** @type {Array<Object>} Array con todos los productos originales */
        let productos = [];
        
        /** @type {Array<Object>} Array con productos después de aplicar filtros */
        let productosFiltrados = [];
        
        /** @type {Object} Objeto que contiene los filtros activos */
        let filtrosActivos = {
            busqueda: '',     // Término de búsqueda
            precioMin: 0,     // Precio mínimo
            precioMax: 1000   // Precio máximo
        };
        
        /** @type {number} Página actual de la paginación */
        let paginaActual = 1;
        
        /** @type {number} Cantidad de productos por página */
        let productosPorPagina = 20;
        
        /** @type {number} Total de páginas calculadas */
        let totalPaginas = 1;

        /**
         * Calcula la cantidad de productos por página según el ancho de pantalla
         * @description Ajusta dinámicamente la cantidad de productos mostrados
         * basándose en las columnas del grid responsivo
         * @returns {number} Número de productos por página
         */
        function calcularProductosPorPagina() {
            const width = window.innerWidth;
            let columnas;
            
            // Determina el número de columnas según el breakpoint
            if (width >= 1024) {        // lg: 4 columnas
                columnas = 4;
            } else if (width >= 640) {  // sm: 2 columnas
                columnas = 2;
            } else {                    // móvil: 1 columna
                columnas = 1;
            }
            
            // 5 filas por página
            return 5 * columnas;
        }

        /**
         * Muestra una notificación temporal en la esquina superior derecha
         * @description Crea y muestra notificaciones con diferentes tipos de estilo
         * @param {string} mensaje - Texto a mostrar en la notificación
         * @param {string} [tipo='success'] - Tipo de notificación: 'success', 'error', 'warning'
         */
        function mostrarNotificacion(mensaje, tipo = 'success') {
            // Crea el elemento de notificación
            const notification = document.createElement('div');
            let bgColor;
            
            // Determina el color según el tipo
            switch(tipo) {
                case 'success':
                    bgColor = 'bg-teal-600';
                    break;
                case 'error':
                    bgColor = 'bg-red-600';
                    break;
                case 'warning':
                    bgColor = 'bg-yellow-500';
                    break;
                default:
                    bgColor = 'bg-teal-600';
            }
            
            // Configura las clases y contenido
            notification.className = `notification fixed top-20 right-5 px-5 py-4 rounded-md text-white font-bold z-50 ${bgColor}`;
            notification.textContent = mensaje;
            document.body.appendChild(notification);
            
            // Animación de entrada
            setTimeout(() => notification.classList.add('show'), 100);
            
            // Auto-eliminación después de 5 segundos
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        /**
         * Inicializa el array de productos y configura los filtros de precio
         * @description Extrae información de los elementos DOM y configura
         * los rangos de precio basándose en los productos disponibles
         */
        function inicializarProductos() {
            // Obtiene todos los elementos de producto del DOM
            const productosElementos = document.querySelectorAll('.producto-item');
            
            // Convierte elementos DOM a objetos JavaScript
            productos = Array.from(productosElementos).map(elemento => ({
                elemento: elemento,
                nombre: elemento.dataset.nombre || '',
                descripcion: elemento.dataset.descripcion || '',
                precio: parseFloat(elemento.dataset.precio) || 0
            }));
            
            // Configura los sliders de precio basándose en el rango real
            if (productos.length > 0) {
                const precios = productos.map(p => p.precio);
                const minPrecio = Math.floor(Math.min(...precios));
                const maxPrecio = Math.ceil(Math.max(...precios));
                
                // Actualiza los sliders
                const minSlider = document.getElementById('minPrice');
                const maxSlider = document.getElementById('maxPrice');
                
                minSlider.min = minPrecio;
                minSlider.max = maxPrecio;
                minSlider.value = minPrecio;
                
                maxSlider.min = minPrecio;
                maxSlider.max = maxPrecio;
                maxSlider.value = maxPrecio;
                
                // Actualiza los filtros activos
                filtrosActivos.precioMin = minPrecio;
                filtrosActivos.precioMax = maxPrecio;
                
                // Actualiza la visualización
                document.getElementById('minPriceDisplay').textContent = minPrecio;
                document.getElementById('maxPriceDisplay').textContent = maxPrecio;
            }
            
            // Calcula productos por página y aplica filtros iniciales
            productosPorPagina = calcularProductosPorPagina();
            aplicarFiltros();
        }

        /**
         * Aplica todos los filtros activos y actualiza la visualización
         * @description Filtra productos según búsqueda y rango de precios,
         * luego actualiza la paginación y muestra los resultados
         */
        function aplicarFiltros() {
            // Filtra productos según los criterios activos
            productosFiltrados = productos.filter(producto => {
                let mostrar = true;
                
                // Filtro de búsqueda por nombre o descripción
                if (filtrosActivos.busqueda) {
                    const terminoBusqueda = filtrosActivos.busqueda.toLowerCase();
                    const coincideNombre = producto.nombre.includes(terminoBusqueda);
                    const coincideDescripcion = producto.descripcion.includes(terminoBusqueda);
                    
                    if (!coincideNombre && !coincideDescripcion) {
                        mostrar = false;
                    }
                }
                
                // Filtro de rango de precios
                if (producto.precio < filtrosActivos.precioMin || producto.precio > filtrosActivos.precioMax) {
                    mostrar = false;
                }
                
                return mostrar;
            });
            
            // Reinicia la paginación y actualiza la vista
            paginaActual = 1;
            actualizarPaginacion();
            mostrarProductosPagina();
        }

        /**
         * Actualiza los controles de paginación
         * @description Calcula el total de páginas, actualiza botones
         * y genera los números de página
         */
        function actualizarPaginacion() {
            // Calcula el total de páginas
            totalPaginas = Math.ceil(productosFiltrados.length / productosPorPagina);
            
            // Muestra u oculta la paginación según sea necesario
            const paginationContainer = document.getElementById('paginationContainer');
            if (totalPaginas <= 1) {
                paginationContainer.style.display = 'none';
            } else {
                paginationContainer.style.display = 'block';
            }
            
            // Actualiza la información de página
            document.getElementById('currentPageInfo').textContent = paginaActual;
            document.getElementById('totalPagesInfo').textContent = totalPaginas;
            
            // Actualiza el estado de los botones anterior/siguiente
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            
            prevBtn.disabled = paginaActual <= 1;
            nextBtn.disabled = paginaActual >= totalPaginas;
            
            // Genera los números de página
            generarNumerosPagina();
        }

        /**
         * Genera los botones numerados de paginación
         * @description Crea dinámicamente los botones de página con lógica
         * para mostrar un rango limitado alrededor de la página actual
         */
        function generarNumerosPagina() {
            const pageNumbersContainer = document.getElementById('pageNumbers');
            pageNumbersContainer.innerHTML = '';
            
            // Calcula el rango de páginas a mostrar (máximo 5)
            let inicio = Math.max(1, paginaActual - 2);
            let fin = Math.min(totalPaginas, paginaActual + 2);
            
            // Ajusta el rango para mantener 5 botones cuando sea posible
            if (fin - inicio < 4) {
                if (inicio === 1) {
                    fin = Math.min(totalPaginas, inicio + 4);
                } else {
                    inicio = Math.max(1, fin - 4);
                }
            }
            
            // Crea los botones de página
            for (let i = inicio; i <= fin; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `pagination-btn ${i === paginaActual ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.addEventListener('click', () => irAPagina(i));
                pageNumbersContainer.appendChild(pageBtn);
            }
        }

        /**
         * Muestra los productos de la página actual
         * @description Oculta todos los productos y muestra solo los
         * correspondientes a la página actual según la paginación
         */
        function mostrarProductosPagina() {
            // Oculta todos los productos
            productos.forEach(producto => {
                producto.elemento.classList.add('hidden');
            });
            
            // Calcula el rango de productos para la página actual
            const inicio = (paginaActual - 1) * productosPorPagina;
            const fin = inicio + productosPorPagina;
            
            // Muestra solo los productos de la página actual
            const productosAPaginar = productosFiltrados.slice(inicio, fin);
            productosAPaginar.forEach(producto => {
                producto.elemento.classList.remove('hidden');
            });
            
            // Maneja la visualización cuando no hay resultados
            const noResults = document.getElementById('noResults');
            const productGrid = document.getElementById('productGrid');
            
            if (productosFiltrados.length === 0) {
                noResults.classList.remove('hidden');
                productGrid.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                productGrid.classList.remove('hidden');
            }
            
            // Actualiza el contador de productos
            document.getElementById('countNumber').textContent = productosFiltrados.length;
        }

        /**
         * Navega a una página específica
         * @description Cambia la página actual y actualiza la visualización,
         * incluyendo scroll suave al inicio de la página
         * @param {number} numeroPagina - Número de página de destino
         */
        function irAPagina(numeroPagina) {
            if (numeroPagina >= 1 && numeroPagina <= totalPaginas) {
                paginaActual = numeroPagina;
                actualizarPaginacion();
                mostrarProductosPagina();
                
                // Scroll suave al inicio de la página
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        /**
         * Restablece todos los filtros a sus valores por defecto
         * @description Limpia el buscador y restablece los sliders de precio
         * a los valores mínimo y máximo de todos los productos
         */
        function limpiarFiltros() {
            // Limpia el campo de búsqueda
            document.getElementById('searchInput').value = '';
            filtrosActivos.busqueda = '';
            
            // Restablece los sliders de precio
            if (productos.length > 0) {
                const precios = productos.map(p => p.precio);
                const minPrecio = Math.floor(Math.min(...precios));
                const maxPrecio = Math.ceil(Math.max(...precios));
                
                document.getElementById('minPrice').value = minPrecio;
                document.getElementById('maxPrice').value = maxPrecio;
                document.getElementById('minPriceDisplay').textContent = minPrecio;
                document.getElementById('maxPriceDisplay').textContent = maxPrecio;
                
                filtrosActivos.precioMin = minPrecio;
                filtrosActivos.precioMax = maxPrecio;
            }
            
            // Reaplica los filtros
            aplicarFiltros();
        }

        /**
         * Función debounce para optimizar el rendimiento
         * @description Retrasa la ejecución de una función hasta que
         * hayan pasado un número específico de milisegundos desde la última llamada
         * @param {Function} func - Función a ejecutar
         * @param {number} wait - Tiempo de espera en milisegundos
         * @returns {Function} Función con debounce aplicado
         */
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        /**
         * === EVENT LISTENERS ===
         * @description Configuración de todos los eventos de la aplicación
         */

        // Listener para cambios de tamaño de ventana (responsivo)
        window.addEventListener('resize', debounce(() => {
            const nuevosProductosPorPagina = calcularProductosPorPagina();
            if (nuevosProductosPorPagina !== productosPorPagina) {
                productosPorPagina = nuevosProductosPorPagina;
                paginaActual = 1;
                actualizarPaginacion();
                mostrarProductosPagina();
            }
        }, 250));

        /**
         * === INICIALIZACIÓN DE LA APLICACIÓN ===
         * @description Se ejecuta cuando el DOM está completamente cargado
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa el sistema de productos y filtros
            inicializarProductos();
            
            // Configura CSRF token para peticiones AJAX (si jQuery está disponible)
            if (typeof $ !== 'undefined') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            }

            /**
             * === CONFIGURACIÓN DE BÚSQUEDA ===
             * @description Aplica debounce al campo de búsqueda para optimizar rendimiento
             */
            const debouncedSearch = debounce((valor) => {
                filtrosActivos.busqueda = valor;
                aplicarFiltros();
            }, 300);
            
            // Event listener para el campo de búsqueda
            document.getElementById('searchInput').addEventListener('input', function() {
                debouncedSearch(this.value);
            });

            /**
             * === CONFIGURACIÓN DE SLIDERS DE PRECIO ===
             * @description Maneja los cambios en los sliders de rango de precio
             */
            const minPriceSlider = document.getElementById('minPrice');
            const maxPriceSlider = document.getElementById('maxPrice');
            const minPriceDisplay = document.getElementById('minPriceDisplay');
            const maxPriceDisplay = document.getElementById('maxPriceDisplay');

            /**
             * Actualiza el rango de precios cuando cambian los sliders
             * @description Valida que el precio mínimo no sea mayor al máximo
             * y actualiza la visualización y filtros
             */
            function actualizarRangoPrecios() {
                let minVal = parseInt(minPriceSlider.value);
                let maxVal = parseInt(maxPriceSlider.value);

                // Validación: el mínimo no puede ser mayor o igual al máximo
                if (minVal >= maxVal) {
                    minVal = maxVal - 1;
                    minPriceSlider.value = minVal;
                }

                // Actualiza la visualización
                minPriceDisplay.textContent = minVal;
                maxPriceDisplay.textContent = maxVal;

                // Actualiza los filtros activos
                filtrosActivos.precioMin = minVal;
                filtrosActivos.precioMax = maxVal;

                // Aplica los filtros
                aplicarFiltros();
            }

            // Event listeners para los sliders de precio
            minPriceSlider.addEventListener('input', actualizarRangoPrecios);
            maxPriceSlider.addEventListener('input', actualizarRangoPrecios);

            /**
             * === CONFIGURACIÓN DE CONTROLES DE FILTRO ===
             */
            // Botón para limpiar filtros
            document.getElementById('resetFilters').addEventListener('click', limpiarFiltros);

            /**
             * === CONFIGURACIÓN DE PAGINACIÓN ===
             */
            // Botón página anterior
            document.getElementById('prevPage').addEventListener('click', () => {
                if (paginaActual > 1) {
                    irAPagina(paginaActual - 1);
                }
            });

            // Botón página siguiente
            document.getElementById('nextPage').addEventListener('click', () => {
                if (paginaActual < totalPaginas) {
                    irAPagina(paginaActual + 1);
                }
            });

            /**
             * === NAVEGACIÓN CON TECLADO ===
             * @description Permite navegar la paginación con las flechas del teclado
             */
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft' && paginaActual > 1) {
                    irAPagina(paginaActual - 1);
                } else if (e.key === 'ArrowRight' && paginaActual < totalPaginas) {
                    irAPagina(paginaActual + 1);
                }
            });

            /**
             * === CONFIGURACIÓN DE BOTONES DE CARRITO ===
             * @description Maneja la funcionalidad de añadir productos al carrito
             */
            document.querySelectorAll('.btn-carrito').forEach(btn => {
                btn.addEventListener('click', function (event) {
                    event.preventDefault();
                    
                    // Verifica si el botón está deshabilitado
                    if (this.disabled) {
                        return;
                    }
                    
                    // Obtiene información del producto
                    const productoId = this.dataset.productoId;
                    const stock = parseInt(this.dataset.stock) || 0;
                    const icono = this.querySelector('.cartIcon');
                    const originalSrc = icono.src;
                    
                    // Verifica stock disponible
                    if (stock <= 0) {
                        mostrarNotificacion('Producto sin stock', 'error');
                        return;
                    }
                    
                    // Deshabilita el botón durante la petición
                    this.disabled = true;
                    
                    // Cambia el icono a loading (GIF animado)
                    const loadingGif = "{{ asset('images/iconos/carrito.gif') }}";
                    icono.src = loadingGif;
                    
                    // Obtiene el token CSRF
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        mostrarNotificacion('Error de configuración', 'error');
                        this.disabled = false;
                        icono.src = originalSrc;
                        return;
                    }

                    const token = csrfToken.getAttribute('content');

                    // Prepara los datos para enviar
                    const formData = new FormData();
                    formData.append('producto_id', productoId);
                    formData.append('cantidad', '1');
                    formData.append('_token', token);
                    
                    /**
                     * === PETICIÓN AJAX AL SERVIDOR ===
                     * @description Envía una petición POST para añadir el producto al carrito
                     */
                    fetch('/carrito/agregar-producto', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        // Maneja diferentes códigos de estado HTTP
                        if (response.status === 401) {
                            // Error de autenticación
                            return response.json().then(data => {
                                throw new Error(JSON.stringify({...data, status: 401}));
                            });
                        }
                        
                        if (!response.ok) {
                            // Error general HTTP
                            return response.text().then(text => {
                                throw new Error(`HTTP error! status: ${response.status} - ${text}`);
                            });
                        }
                        
                        // Verifica que la respuesta sea JSON válido
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            return response.text().then(text => {
                                throw new Error('El servidor no devolvió una respuesta JSON válida');
                            });
                        }
                    })
                    .then(data => {
                        // Maneja respuesta exitosa
                        if (data.success) {
                            mostrarNotificacion(data.message || 'Producto añadido al carrito', 'success');
                            
                            /**
                             * === ACTUALIZACIÓN DEL CONTADOR DEL CARRITO ===
                             * @description Actualiza el contador visual del carrito en la UI
                             */
                            if (data.carrito_count !== undefined) {
                                const contadorCarrito = document.querySelector('.cart-count');
                                if (contadorCarrito) {
                                    contadorCarrito.textContent = data.carrito_count;
                                    if (data.carrito_count > 0) {
                                        contadorCarrito.style.display = 'flex';
                                    }
                                } else if (data.carrito_count > 0) {
                                    // Crea el contador si no existe
                                    const cartItem = document.querySelector('.cart-menu-item');
                                    if (cartItem && !cartItem.querySelector('.cart-count')) {
                                        const counter = document.createElement('span');
                                        counter.className = 'cart-count';
                                        counter.textContent = data.carrito_count;
                                        cartItem.appendChild(counter);
                                    }
                                }
                            }
                        } else {
                            // Maneja errores del servidor
                            let mensajeError = data.message || 'Error al añadir producto al carrito';
                            
                            // Personaliza mensajes relacionados con stock
                            if (mensajeError.toLowerCase().includes('stock') || 
                                mensajeError.toLowerCase().includes('disponible') ||
                                mensajeError.toLowerCase().includes('suficiente')) {
                                mensajeError = 'No se ha podido añadir, stock insuficiente';
                            }
                            mostrarNotificacion(mensajeError, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        
                        try {
                            // Intenta parsear el error como JSON
                            const errorData = JSON.parse(error.message);
                            if (errorData.status === 401 && errorData.type === 'auth_required') {
                                // Error específico de autenticación
                                mostrarNotificacion('Debes iniciar sesión para añadir productos al carrito', 'warning');
                                
                                // Opcional: Redirigir al login después del mensaje
                                setTimeout(() => {
                                    window.location.href = errorData.redirect || '/login';
                                }, 2000);
                            } else {
                                mostrarNotificacion(errorData.message || 'Error al añadir al carrito', 'error');
                            }
                        } catch (parseError) {
                            // Error genérico si no se puede parsear
                            mostrarNotificacion('Error: no se pudo añadir al carrito', 'error');
                        }
                    })
                    .finally(() => {
                        // Restaura el botón después de 1 segundo
                        setTimeout(() => {
                            icono.src = originalSrc;
                            this.disabled = false;
                        }, 1000);
                    });
                });
            });
        });
    </script>
</body>
</html>