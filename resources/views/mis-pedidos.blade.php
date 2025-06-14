<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos</title>
    
    <!-- === LIBRERÍAS EXTERNAS === -->
    <!-- Bootstrap CSS para diseño responsivo y componentes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- SweetAlert2 para modales y notificaciones elegantes -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* === ESTILOS PERSONALIZADOS === */
        
        /* Efecto hover en tarjetas de pedidos */
        .card {
            transition: transform 0.2s; /* Transición suave */
        }
        
        .card:hover {
            transform: translateY(-2px); /* Elevación sutil al hacer hover */
        }
        
        /* Tamaño de fuente para badges de estado */
        .badge {
            font-size: 0.85em;
        }
        
        /* Borde para imágenes en tablas */
        .table img {
            border: 1px solid #dee2e6;
        }
        
        /* Sobrescribe el color de fondo de Bootstrap */
        .bg-light {
            background-color: #f8f9fa !important;
        }
        
        /* Sobrescribe el color primario de Bootstrap */
        .text-primary {
            color: #0d6efd !important;
        }
        
        /* Tamaño personalizado para spinner de carga */
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        
        /* Fondo general de la página */
        body {
            background-color: #f8f9fa;
        }
        
        /* Sombra para la barra de navegación */
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        /* Altura mínima para el contenido principal */
        .main-content {
            min-height: calc(100vh - 120px);
        }
    </style>
</head>
<body>
    <!-- === BANNER/NAVEGACIÓN === -->
    <!-- Incluye el componente banner de Laravel Blade -->
    @include('banner')

    <!-- === CONTENIDO PRINCIPAL === -->
    <div class="main-content">
        <div class="container my-5">
            
            <!-- === ENCABEZADO DE PÁGINA === -->
            <div class="row">
                <div class="col-12">
                    <!-- Título y botón de navegación en una fila -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">
                            <i class="fas fa-shopping-bag me-2 text-primary"></i>
                            Mis Pedidos
                        </h2>
                        <!-- Botón para volver al catálogo -->
                        <a href="/catalogo" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Volver al Catálogo
                        </a>
                    </div>
                </div>
            </div>

            <!-- === SECCIÓN DE BÚSQUEDA Y INFORMACIÓN === -->
            <div class="row mb-4">
                
                <!-- === FORMULARIO DE BÚSQUEDA POR DNI === -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-search me-2"></i>
                                Consultar Pedidos
                            </h5>
                            <!-- Formulario para buscar pedidos por DNI -->
                            <form id="form-buscar-pedidos">
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="dni-input" 
                                           placeholder="Introduce tu DNI" 
                                           required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                        Buscar
                                    </button>
                                </div>
                                <small class="text-muted">
                                    Introduce tu DNI para ver todos tus pedidos
                                </small>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- === INFORMACIÓN DE ESTADOS === -->
                <div class="col-md-6">
                    <!-- Tarjeta informativa sobre los estados de pedido -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Estados de Pedido
                            </h6>
                            <!-- Grid con iconos y descripciones de estados -->
                            <div class="row">
                                <div class="col-4 text-center">
                                    <i class="fas fa-clock text-info fa-2x"></i>
                                    <small class="d-block text-muted">Preparando</small>
                                </div>
                                <div class="col-4 text-center">
                                    <i class="fas fa-truck text-warning fa-2x"></i>
                                    <small class="d-block text-muted">En camino</small>
                                </div>
                                <div class="col-4 text-center">
                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                    <small class="d-block text-muted">Entregado</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- === INDICADOR DE CARGA === -->
            <!-- Spinner que se muestra mientras se cargan los pedidos -->
            <div id="loading-spinner" class="text-center d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando pedidos...</p>
            </div>

            <!-- === MENSAJE SIN PEDIDOS === -->
            <!-- Se muestra cuando no hay pedidos para el DNI consultado -->
            <div id="sin-pedidos" class="text-center d-none">
                <div class="card shadow-sm">
                    <div class="card-body py-5">
                        <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No tienes pedidos</h5>
                        <p class="text-muted">Cuando realices tu primer pedido, aparecerá aquí.</p>
                        <a href="/catalogo" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Explorar Productos
                        </a>
                    </div>
                </div>
            </div>

            <!-- === LISTA DE PEDIDOS === -->
            <!-- Contenedor que muestra los pedidos encontrados -->
            <div id="lista-pedidos" class="d-none">
                <div class="row">
                    <div class="col-12">
                        <!-- Título y contador de pedidos -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="titulo-resultados">Tus Pedidos</h4>
                            <span id="contador-pedidos" class="badge bg-primary"></span>
                        </div>
                    </div>
                </div>
                <!-- Contenedor donde se insertan dinámicamente las tarjetas de pedidos -->
                <div id="contenedor-pedidos">
                    <!-- Los pedidos se cargarán aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <!-- === MODAL DE DETALLES DEL PEDIDO === -->
    <!-- Modal Bootstrap para mostrar información detallada de un pedido -->
    <div class="modal fade" id="modal-detalle-pedido" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-receipt me-2"></i>
                        Detalles del Pedido
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- El contenido se genera dinámicamente con JavaScript -->
                <div class="modal-body" id="contenido-detalle-pedido">
                    <!-- El contenido se cargará dinámicamente -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- === FOOTER === -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6>Mi Tienda Online</h6>
                    <p class="mb-0">Tu tienda de confianza desde 2024</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2024 Mi Tienda. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- === SCRIPTS === -->
    <!-- jQuery para manipulación DOM y AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS para componentes interactivos -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 para alertas elegantes -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    /**
     * === INICIALIZACIÓN DE LA APLICACIÓN ===
     * @description Se ejecuta cuando el DOM está completamente cargado
     */
    $(document).ready(function() {
        
        /**
         * === MANEJO DEL FORMULARIO DE BÚSQUEDA ===
         * @description Event listener para el formulario de búsqueda por DNI
         */
        $('#form-buscar-pedidos').on('submit', function(e) {
            e.preventDefault(); // Previene el envío tradicional del formulario
            
            // Obtiene y limpia el valor del DNI
            const dni = $('#dni-input').val().trim();
            
            // Validación: verifica que se haya introducido un DNI
            if (!dni) {
                Swal.fire({
                    icon: 'warning',
                    title: 'DNI requerido',
                    text: 'Por favor, introduce tu DNI para buscar tus pedidos.'
                });
                return;
            }
            
            // Inicia la búsqueda de pedidos
            buscarPedidos(dni);
        });

        /**
         * Realiza una petición AJAX para buscar pedidos por DNI
         * @description Envía una petición GET al endpoint de la API
         * y maneja las diferentes respuestas posibles
         * @param {string} dni - DNI del usuario para buscar pedidos
         */
        function buscarPedidos(dni) {
            // === MANEJO DE ESTADOS DE UI ===
            // Muestra el spinner de carga y oculta otros contenedores
            $('#loading-spinner').removeClass('d-none');
            $('#lista-pedidos').addClass('d-none');
            $('#sin-pedidos').addClass('d-none');

            /**
             * === PETICIÓN AJAX ===
             * @description Realiza llamada a la API para obtener pedidos
             */
            $.ajax({
                url: '/api/mis-pedidos',
                method: 'GET',
                data: { dni: dni },
                
                /**
                 * Maneja la respuesta exitosa de la API
                 * @param {Object} response - Respuesta del servidor
                 * @param {boolean} response.success - Indica si la operación fue exitosa
                 * @param {Array} response.pedidos - Array con los pedidos encontrados
                 * @param {number} response.total_pedidos - Total de pedidos
                 */
                success: function(response) {
                    // Oculta el spinner de carga
                    $('#loading-spinner').addClass('d-none');
                    
                    // Verifica si hay pedidos en la respuesta
                    if (response.success && response.pedidos.length > 0) {
                        // Muestra los pedidos encontrados
                        mostrarPedidos(response.pedidos, response.total_pedidos);
                    } else {
                        // Muestra mensaje de "sin pedidos"
                        $('#sin-pedidos').removeClass('d-none');
                    }
                },
                
                /**
                 * Maneja errores de la petición AJAX
                 * @param {Object} xhr - Objeto XMLHttpRequest con información del error
                 */
                error: function(xhr) {
                    // Oculta el spinner de carga
                    $('#loading-spinner').addClass('d-none');
                    
                    // Manejo específico para error 401 (No autorizado)
                    if (xhr.status === 401) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sesión requerida',
                            text: 'Debes iniciar sesión para ver tus pedidos.',
                            confirmButtonText: 'Ir al login'
                        }).then(() => {
                            // Redirige al login después de confirmar
                            window.location.href = '/login';
                        });
                    } else {
                        // Manejo de otros errores
                        const errorMsg = xhr.responseJSON?.message || 'Error al cargar los pedidos';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    }
                }
            });
        }

        /**
         * Muestra la lista de pedidos en la interfaz
         * @description Genera y muestra las tarjetas de pedidos
         * @param {Array} pedidos - Array de objetos pedido
         * @param {number} total - Total de pedidos
         */
        function mostrarPedidos(pedidos, total) {
            const contenedor = $('#contenedor-pedidos');
            contenedor.empty(); // Limpia el contenedor
            
            // Actualiza el contador de pedidos con pluralización
            $('#contador-pedidos').text(`${total} pedido${total !== 1 ? 's' : ''}`);
            
            // Genera una tarjeta para cada pedido
            pedidos.forEach(function(pedido) {
                const card = crearTarjetaPedido(pedido);
                contenedor.append(card);
            });
            
            // Muestra el contenedor de pedidos
            $('#lista-pedidos').removeClass('d-none');
        }

        /**
         * Crea el HTML de una tarjeta de pedido
         * @description Genera una tarjeta Bootstrap con información del pedido
         * @param {Object} pedido - Objeto con datos del pedido
         * @param {string} pedido.id - ID del pedido
         * @param {Object} pedido.estado - Estado del pedido con clase CSS e icono
         * @param {string} pedido.fecha_formateada - Fecha del pedido formateada
         * @param {number} pedido.cantidad_productos - Cantidad de productos
         * @param {number} pedido.precio - Precio total del pedido
         * @param {Array} pedido.productos - Array de productos del pedido
         * @returns {string} HTML de la tarjeta del pedido
         */
        function crearTarjetaPedido(pedido) {
            const estadoClass = `text-${pedido.estado.clase_css}`;
            const estadoIcon = pedido.estado.icono;
            
            return `
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <!-- === INFORMACIÓN PRINCIPAL DEL PEDIDO === -->
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <!-- Número de pedido y estado -->
                                <div class="d-flex align-items-center mb-2">
                                    <h5 class="mb-0 me-3">Pedido #${pedido.id}</h5>
                                    <span class="badge bg-${pedido.estado.clase_css}">
                                        <i class="fas ${estadoIcon} me-1"></i>
                                        ${pedido.estado.mensaje}
                                    </span>
                                </div>
                                <!-- Fecha del pedido -->
                                <p class="text-muted mb-1">
                                    <i class="fas fa-calendar me-1"></i>
                                    ${pedido.fecha_formateada}
                                </p>
                                <!-- Cantidad de productos -->
                                <p class="text-muted mb-1">
                                    <i class="fas fa-box me-1"></i>
                                    ${pedido.cantidad_productos} producto${pedido.cantidad_productos !== 1 ? 's' : ''}
                                </p>
                            </div>
                            <!-- === PRECIO Y ACCIONES === -->
                            <div class="col-md-4 text-md-end">
                                <!-- Precio total -->
                                <h4 class="text-primary mb-2">€${parseFloat(pedido.precio).toFixed(2)}</h4>
                                <!-- Botón para ver detalles -->
                                <button class="btn btn-outline-primary btn-sm" 
                                        onclick="verDetallePedido('${pedido.id}')">
                                    <i class="fas fa-eye me-1"></i>
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                        
                        <!-- === VISTA PREVIA DE PRODUCTOS === -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <small class="text-muted d-block mb-2">Productos:</small>
                                <div class="d-flex flex-wrap gap-2">
                                    <!-- Muestra los primeros 3 productos -->
                                    ${pedido.productos.slice(0, 3).map(producto => `
                                        <div class="d-flex align-items-center bg-light rounded px-2 py-1">
                                            ${producto.imagen ? `
                                                <img src="${producto.imagen}" 
                                                     alt="${producto.nombre}" 
                                                     class="me-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">
                                            ` : ''}
                                            <small>${producto.nombre} (${producto.cantidad})</small>
                                        </div>
                                    `).join('')}
                                    <!-- Indicador si hay más productos -->
                                    ${pedido.productos.length > 3 ? `
                                        <div class="d-flex align-items-center text-muted">
                                            <small>+${pedido.productos.length - 3} más</small>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        /**
         * === FUNCIÓN GLOBAL PARA VER DETALLES ===
         * @description Función global accesible desde los botones generados dinámicamente
         * @param {string} idPedido - ID del pedido a consultar
         */
        window.verDetallePedido = function(idPedido) {
            /**
             * Petición AJAX para obtener detalles completos del pedido
             */
            $.ajax({
                url: `/api/pedido/${idPedido}`,
                method: 'GET',
                
                /**
                 * Maneja respuesta exitosa
                 * @param {Object} response - Respuesta del servidor
                 */
                success: function(response) {
                    if (response.success) {
                        // Muestra el modal con los detalles del pedido
                        mostrarDetallePedido(response.pedido);
                    }
                },
                
                /**
                 * Maneja errores de la petición
                 * @param {Object} xhr - Objeto XMLHttpRequest
                 */
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || 'Error al cargar el pedido';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg
                    });
                }
            });
        };

        /**
         * Muestra los detalles completos del pedido en un modal
         * @description Genera el HTML completo para el modal de detalles
         * @param {Object} pedido - Objeto con todos los datos del pedido
         */
        function mostrarDetallePedido(pedido) {
            const estadoClass = `text-${pedido.estado.clase_css}`;
            const estadoIcon = pedido.estado.icono;
            
            /**
             * === GENERACIÓN DE CONTENIDO DEL MODAL ===
             * @description Template literal que genera HTML completo
             */
            const contenido = `
                <!-- === INFORMACIÓN GENERAL === -->
                <div class="row mb-4">
                    <!-- Información del pedido -->
                    <div class="col-md-6">
                        <h6>Información del Pedido</h6>
                        <p><strong>Número:</strong> ${pedido.id}</p>
                        <p><strong>Fecha:</strong> ${pedido.fecha_formateada}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-${pedido.estado.clase_css}">
                                <i class="fas ${estadoIcon} me-1"></i>
                                ${pedido.estado.mensaje}
                            </span>
                        </p>
                        <p><strong>Total:</strong> <span class="text-primary fs-5">€${parseFloat(pedido.precio).toFixed(2)}</span></p>
                    </div>
                    <!-- Información de envío -->
                    <div class="col-md-6">
                        <h6>Información de Envío</h6>
                        ${pedido.direccion ? `<p><strong>Dirección:</strong> ${pedido.direccion}</p>` : ''}
                        ${pedido.ciudad ? `<p><strong>Ciudad:</strong> ${pedido.ciudad}</p>` : ''}
                        ${pedido.codigo_postal ? `<p><strong>Código Postal:</strong> ${pedido.codigo_postal}</p>` : ''}
                        ${pedido.metodo_pago ? `<p><strong>Método de Pago:</strong> ${pedido.metodo_pago}</p>` : ''}
                    </div>
                </div>
                
                <hr>
                
                <!-- === TABLA DE PRODUCTOS === -->
                <h6>Productos Pedidos</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Precio Unit.</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Genera una fila por cada producto -->
                            ${pedido.productos.map(producto => `
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            ${producto.imagen ? `
                                                <img src="${producto.imagen}" 
                                                     alt="${producto.nombre}" 
                                                     class="me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            ` : ''}
                                            <span>${producto.nombre}</span>
                                        </div>
                                    </td>
                                    <td>€${parseFloat(producto.precio).toFixed(2)}</td>
                                    <td>${producto.cantidad}</td>
                                    <td>€${parseFloat(producto.subtotal).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <!-- Pie de tabla con total -->
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3">Total</th>
                                <th>€${parseFloat(pedido.precio).toFixed(2)}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
            
            // Inserta el contenido en el modal y lo muestra
            $('#contenido-detalle-pedido').html(contenido);
            $('#modal-detalle-pedido').modal('show');
        }
    });
    </script>
</body>
</html>