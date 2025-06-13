<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Catálogo de Productos</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .notification {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        .notification.show {
            transform: translateX(0);
        }
        .cartIcon {
            transition: transform 0.2s ease;
        }
        .cartIcon:hover {
            transform: scale(1.2);
        }
        .btn-carrito:disabled .cartIcon:hover {
            transform: none;
        }
        
        .price-slider {
            -webkit-appearance: none;
            appearance: none;
            height: 6px;
            border-radius: 3px;
            background: #e5e7eb;
            outline: none;
        }
        
        .price-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #0d9488;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .price-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #0d9488;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .producto-item {
            transition: all 0.3s ease;
        }
        
        .producto-item.hidden {
            opacity: 0;
            transform: scale(0.8);
            display: none;
        }
        
        .filters-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 1px solid #e2e8f0;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

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

        .pagination-btn:hover:not(:disabled) {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .pagination-btn.active {
            background: #0d9488;
            color: white;
            border-color: #0d9488;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-info {
            color: #6b7280;
            font-size: 0.875rem;
        }
    </style>
</head>
@include('banner')
<body class="font-sans m-0 p-0 bg-gray-50">

    <h1 class="text-center mb-8 mt-8 mx-5 text-3xl font-bold text-gray-800">Catálogo de Productos en Venta</h1>
    
    <!-- Panel de Filtros -->
    <div class="filters-container max-w-6xl mx-auto px-5 mb-8 p-6 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Buscador -->
            <div class="flex flex-col">
                <label for="searchInput" class="text-sm font-semibold text-gray-700 mb-2">Buscar productos</label>
                <div class="relative">
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Buscar por nombre o descripción..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                    />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Filtro de Precio -->
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-gray-700 mb-2">Rango de precios</label>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Mínimo: <span id="minPriceDisplay">0</span>€</span>
                        <span>Máximo: <span id="maxPriceDisplay">1000</span>€</span>
                    </div>
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

            <!-- Controles de Filtro -->
            <div class="flex flex-col justify-end">
                <div class="flex space-x-3">
                    <button 
                        id="resetFilters" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium"
                    >
                        Limpiar filtros
                    </button>
                    <div id="resultsCount" class="flex items-center px-4 py-2 bg-teal-50 text-teal-700 rounded-lg font-medium">
                        <span id="countNumber">0</span> productos
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($productos->isEmpty())
        <p class="text-center text-gray-600 px-5 mb-16">No hay productos disponibles en venta.</p>
    @else
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 max-w-6xl mx-auto px-5">
            @foreach($productos as $producto)
                <div class="producto-item bg-white border border-gray-300 rounded-lg p-4 shadow-md flex flex-col justify-between transition-shadow duration-300 hover:shadow-lg"
                     data-nombre="{{ strtolower($producto->Nombre) }}"
                     data-descripcion="{{ strtolower($producto->Descripcion ?? '') }}"
                     data-precio="{{ $producto->Precio }}">
                    <a href="{{ route('producto.show', $producto->IDProducto) }}">
                        @if($producto->URL)
                        <img src="{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}" alt="{{ $producto->Nombre }}" class="w-full h-38 object-contain mb-4 bg-gray-50 rounded-md" />
                        @else
                        <div class="w-full h-38 flex items-center justify-center text-gray-400 bg-gray-50 rounded-md mb-4">Sin imagen</div>
                        @endif
                    </a>
                    <div class="font-bold text-lg mb-3 text-gray-800 text-center">{{ $producto->Nombre }}</div>
                    
                    @if($producto->Descripcion)
                    <div class="text-sm text-gray-600 mb-3 text-center line-clamp-2">{{ $producto->Descripcion }}</div>
                    @endif
                    
                    <div class="flex justify-center items-center mt-3 gap-32 sm:gap-16 lg:gap-32">
                        <div class="text-center">
                            <div class="font-bold text-teal-600 text-lg">{{ number_format($producto->Precio, 2) }}€</div>
                        </div>
                        <button type="button" class="btn-carrito bg-transparent border-none p-0 relative cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" 
                                data-producto-id="{{ $producto->IDProducto }}"
                                data-stock="{{ $producto->Stock ?? 0 }}"
                                {{ ($producto->Stock ?? 0) <= 0 ? 'disabled' : '' }}>
                            <img class="cartIcon h-5 w-5" src="{{ asset('images/iconos/carrito.png') }}" alt="Añadir al carrito" />
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Paginación -->
        <div id="paginationContainer" class="max-w-6xl mx-auto px-5 mt-12 mb-16">
            <div class="flex flex-col items-center space-y-4">
                <div class="pagination-container">
                    <button id="prevPage" class="pagination-btn" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    
                    <div id="pageNumbers" class="flex gap-1"></div>
                    
                    <button id="nextPage" class="pagination-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="pagination-info text-center">
                    Página <span id="currentPageInfo">1</span> de <span id="totalPagesInfo">1</span>
                </div>
            </div>
        </div>
        
        <!-- Mensaje cuando no hay resultados -->
        <div id="noResults" class="hidden text-center py-12 mb-16">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron productos</h3>
                <p class="text-gray-500">Intenta ajustar los filtros o buscar con otros términos.</p>
            </div>
        </div>
    @endif

    <script>
        let productos = [];
        let productosFiltrados = [];
        let filtrosActivos = {
            busqueda: '',
            precioMin: 0,
            precioMax: 1000
        };
        
        let paginaActual = 1;
        let productosPorPagina = 20;
        let totalPaginas = 1;

        function calcularProductosPorPagina() {
            const width = window.innerWidth;
            let columnas;
            
            if (width >= 1024) {
                columnas = 4;
            } else if (width >= 640) {
                columnas = 2;
            } else {
                columnas = 1;
            }
            
            return 5 * columnas;
        }

        function mostrarNotificacion(mensaje, tipo = 'success') {
            const notification = document.createElement('div');
            let bgColor;
            
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
            
            notification.className = `notification fixed top-20 right-5 px-5 py-4 rounded-md text-white font-bold z-50 ${bgColor}`;
            notification.textContent = mensaje;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.classList.add('show'), 100);
            
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        function inicializarProductos() {
            const productosElementos = document.querySelectorAll('.producto-item');
            productos = Array.from(productosElementos).map(elemento => ({
                elemento: elemento,
                nombre: elemento.dataset.nombre || '',
                descripcion: elemento.dataset.descripcion || '',
                precio: parseFloat(elemento.dataset.precio) || 0
            }));
            
            if (productos.length > 0) {
                const precios = productos.map(p => p.precio);
                const minPrecio = Math.floor(Math.min(...precios));
                const maxPrecio = Math.ceil(Math.max(...precios));
                
                const minSlider = document.getElementById('minPrice');
                const maxSlider = document.getElementById('maxPrice');
                
                minSlider.min = minPrecio;
                minSlider.max = maxPrecio;
                minSlider.value = minPrecio;
                
                maxSlider.min = minPrecio;
                maxSlider.max = maxPrecio;
                maxSlider.value = maxPrecio;
                
                filtrosActivos.precioMin = minPrecio;
                filtrosActivos.precioMax = maxPrecio;
                
                document.getElementById('minPriceDisplay').textContent = minPrecio;
                document.getElementById('maxPriceDisplay').textContent = maxPrecio;
            }
            
            productosPorPagina = calcularProductosPorPagina();
            aplicarFiltros();
        }

        function aplicarFiltros() {
            productosFiltrados = productos.filter(producto => {
                let mostrar = true;
                
                if (filtrosActivos.busqueda) {
                    const terminoBusqueda = filtrosActivos.busqueda.toLowerCase();
                    const coincideNombre = producto.nombre.includes(terminoBusqueda);
                    const coincideDescripcion = producto.descripcion.includes(terminoBusqueda);
                    
                    if (!coincideNombre && !coincideDescripcion) {
                        mostrar = false;
                    }
                }
                
                if (producto.precio < filtrosActivos.precioMin || producto.precio > filtrosActivos.precioMax) {
                    mostrar = false;
                }
                
                return mostrar;
            });
            
            paginaActual = 1;
            actualizarPaginacion();
            mostrarProductosPagina();
        }

        function actualizarPaginacion() {
            totalPaginas = Math.ceil(productosFiltrados.length / productosPorPagina);
            
            const paginationContainer = document.getElementById('paginationContainer');
            if (totalPaginas <= 1) {
                paginationContainer.style.display = 'none';
            } else {
                paginationContainer.style.display = 'block';
            }
            
            document.getElementById('currentPageInfo').textContent = paginaActual;
            document.getElementById('totalPagesInfo').textContent = totalPaginas;
            
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            
            prevBtn.disabled = paginaActual <= 1;
            nextBtn.disabled = paginaActual >= totalPaginas;
            
            generarNumerosPagina();
        }

        function generarNumerosPagina() {
            const pageNumbersContainer = document.getElementById('pageNumbers');
            pageNumbersContainer.innerHTML = '';
            
            let inicio = Math.max(1, paginaActual - 2);
            let fin = Math.min(totalPaginas, paginaActual + 2);
            
            if (fin - inicio < 4) {
                if (inicio === 1) {
                    fin = Math.min(totalPaginas, inicio + 4);
                } else {
                    inicio = Math.max(1, fin - 4);
                }
            }
            
            for (let i = inicio; i <= fin; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `pagination-btn ${i === paginaActual ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.addEventListener('click', () => irAPagina(i));
                pageNumbersContainer.appendChild(pageBtn);
            }
        }

        function mostrarProductosPagina() {
            productos.forEach(producto => {
                producto.elemento.classList.add('hidden');
            });
            
            const inicio = (paginaActual - 1) * productosPorPagina;
            const fin = inicio + productosPorPagina;
            
            const productosAPaginar = productosFiltrados.slice(inicio, fin);
            productosAPaginar.forEach(producto => {
                producto.elemento.classList.remove('hidden');
            });
            
            const noResults = document.getElementById('noResults');
            const productGrid = document.getElementById('productGrid');
            
            if (productosFiltrados.length === 0) {
                noResults.classList.remove('hidden');
                productGrid.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                productGrid.classList.remove('hidden');
            }
            
            document.getElementById('countNumber').textContent = productosFiltrados.length;
        }

        function irAPagina(numeroPagina) {
            if (numeroPagina >= 1 && numeroPagina <= totalPaginas) {
                paginaActual = numeroPagina;
                actualizarPaginacion();
                mostrarProductosPagina();
                
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        function limpiarFiltros() {
            document.getElementById('searchInput').value = '';
            filtrosActivos.busqueda = '';
            
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
            
            aplicarFiltros();
        }

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

        window.addEventListener('resize', debounce(() => {
            const nuevosProductosPorPagina = calcularProductosPorPagina();
            if (nuevosProductosPorPagina !== productosPorPagina) {
                productosPorPagina = nuevosProductosPorPagina;
                paginaActual = 1;
                actualizarPaginacion();
                mostrarProductosPagina();
            }
        }, 250));

        document.addEventListener('DOMContentLoaded', function() {
            inicializarProductos();
            
            if (typeof $ !== 'undefined') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            }

            const debouncedSearch = debounce((valor) => {
                filtrosActivos.busqueda = valor;
                aplicarFiltros();
            }, 300);
            
            document.getElementById('searchInput').addEventListener('input', function() {
                debouncedSearch(this.value);
            });

            const minPriceSlider = document.getElementById('minPrice');
            const maxPriceSlider = document.getElementById('maxPrice');
            const minPriceDisplay = document.getElementById('minPriceDisplay');
            const maxPriceDisplay = document.getElementById('maxPriceDisplay');

            function actualizarRangoPrecios() {
                let minVal = parseInt(minPriceSlider.value);
                let maxVal = parseInt(maxPriceSlider.value);

                if (minVal >= maxVal) {
                    minVal = maxVal - 1;
                    minPriceSlider.value = minVal;
                }

                minPriceDisplay.textContent = minVal;
                maxPriceDisplay.textContent = maxVal;

                filtrosActivos.precioMin = minVal;
                filtrosActivos.precioMax = maxVal;

                aplicarFiltros();
            }

            minPriceSlider.addEventListener('input', actualizarRangoPrecios);
            maxPriceSlider.addEventListener('input', actualizarRangoPrecios);

            document.getElementById('resetFilters').addEventListener('click', limpiarFiltros);

            document.getElementById('prevPage').addEventListener('click', () => {
                if (paginaActual > 1) {
                    irAPagina(paginaActual - 1);
                }
            });

            document.getElementById('nextPage').addEventListener('click', () => {
                if (paginaActual < totalPaginas) {
                    irAPagina(paginaActual + 1);
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft' && paginaActual > 1) {
                    irAPagina(paginaActual - 1);
                } else if (e.key === 'ArrowRight' && paginaActual < totalPaginas) {
                    irAPagina(paginaActual + 1);
                }
            });

            document.querySelectorAll('.btn-carrito').forEach(btn => {
                btn.addEventListener('click', function (event) {
                    event.preventDefault();
                    
                    if (this.disabled) {
                        return;
                    }
                    
                    const productoId = this.dataset.productoId;
                    const stock = parseInt(this.dataset.stock) || 0;
                    const icono = this.querySelector('.cartIcon');
                    const originalSrc = icono.src;
                    
                    if (stock <= 0) {
                        mostrarNotificacion('Producto sin stock', 'error');
                        return;
                    }
                    
                    this.disabled = true;
                    
                    const loadingGif = "{{ asset('images/iconos/carrito.gif') }}";
                    icono.src = loadingGif;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        mostrarNotificacion('Error de configuración', 'error');
                        this.disabled = false;
                        icono.src = originalSrc;
                        return;
                    }

                    const token = csrfToken.getAttribute('content');

                    const formData = new FormData();
                    formData.append('producto_id', productoId);
                    formData.append('cantidad', '1');
                    formData.append('_token', token);
                    
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
                        // Manejar diferentes códigos de estado
                        if (response.status === 401) {
                            return response.json().then(data => {
                                throw new Error(JSON.stringify({...data, status: 401}));
                            });
                        }
                        
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP error! status: ${response.status} - ${text}`);
                            });
                        }
                        
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
                        if (data.success) {
                            mostrarNotificacion(data.message || 'Producto añadido al carrito', 'success');
                            
                            if (data.carrito_count !== undefined) {
                                const contadorCarrito = document.querySelector('.cart-count');
                                if (contadorCarrito) {
                                    contadorCarrito.textContent = data.carrito_count;
                                    if (data.carrito_count > 0) {
                                        contadorCarrito.style.display = 'flex';
                                    }
                                } else if (data.carrito_count > 0) {
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
                            let mensajeError = data.message || 'Error al añadir producto al carrito';
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
                            const errorData = JSON.parse(error.message);
                            if (errorData.status === 401 && errorData.type === 'auth_required') {
                                // Error específico de autenticación
                                mostrarNotificacion('Debes iniciar sesión para añadir productos al carrito', 'warning');
                                
                                // Opcional: Redirigir al login después de mostrar el mensaje
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