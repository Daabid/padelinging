<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        .card {
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .badge {
            font-size: 0.85em;
        }
        
        .table img {
            border: 1px solid #dee2e6;
        }
        
        .bg-light {
            background-color: #f8f9fa !important;
        }
        
        .text-primary {
            color: #0d6efd !important;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .main-content {
            min-height: calc(100vh - 120px);
        }
    </style>
</head>
<body>
    @include('banner')

    <!-- Contenido principal -->
    <div class="main-content">
        <div class="container my-5">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">
                            <i class="fas fa-shopping-bag me-2 text-primary"></i>
                            Mis Pedidos
                        </h2>
                        <a href="/catalogo" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Volver al Catálogo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario de búsqueda por DNI -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-search me-2"></i>
                                Consultar Pedidos
                            </h5>
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
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Estados de Pedido
                            </h6>
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

            <!-- Loading spinner -->
            <div id="loading-spinner" class="text-center d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando pedidos...</p>
            </div>

            <!-- Mensaje de sin pedidos -->
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

            <!-- Lista de pedidos -->
            <div id="lista-pedidos" class="d-none">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="titulo-resultados">Tus Pedidos</h4>
                            <span id="contador-pedidos" class="badge bg-primary"></span>
                        </div>
                    </div>
                </div>
                <div id="contenedor-pedidos">
                    <!-- Los pedidos se cargarán aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de detalles del pedido -->
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

    <!-- Footer básico -->
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

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        // Manejar el formulario de búsqueda
        $('#form-buscar-pedidos').on('submit', function(e) {
            e.preventDefault();
            const dni = $('#dni-input').val().trim();
            
            if (!dni) {
                Swal.fire({
                    icon: 'warning',
                    title: 'DNI requerido',
                    text: 'Por favor, introduce tu DNI para buscar tus pedidos.'
                });
                return;
            }
            
            buscarPedidos(dni);
        });

        function buscarPedidos(dni) {
            // Mostrar loading
            $('#loading-spinner').removeClass('d-none');
            $('#lista-pedidos').addClass('d-none');
            $('#sin-pedidos').addClass('d-none');

            $.ajax({
                url: '/api/mis-pedidos',
                method: 'GET',
                data: { dni: dni },
                success: function(response) {
                    $('#loading-spinner').addClass('d-none');
                    
                    if (response.success && response.pedidos.length > 0) {
                        mostrarPedidos(response.pedidos, response.total_pedidos);
                    } else {
                        $('#sin-pedidos').removeClass('d-none');
                    }
                },
                error: function(xhr) {
                    $('#loading-spinner').addClass('d-none');
                    
                    if (xhr.status === 401) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sesión requerida',
                            text: 'Debes iniciar sesión para ver tus pedidos.',
                            confirmButtonText: 'Ir al login'
                        }).then(() => {
                            window.location.href = '/login';
                        });
                    } else {
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

        function mostrarPedidos(pedidos, total) {
            const contenedor = $('#contenedor-pedidos');
            contenedor.empty();
            
            $('#contador-pedidos').text(`${total} pedido${total !== 1 ? 's' : ''}`);
            
            pedidos.forEach(function(pedido) {
                const card = crearTarjetaPedido(pedido);
                contenedor.append(card);
            });
            
            $('#lista-pedidos').removeClass('d-none');
        }

        function crearTarjetaPedido(pedido) {
            const estadoClass = `text-${pedido.estado.clase_css}`;
            const estadoIcon = pedido.estado.icono;
            
            return `
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    <h5 class="mb-0 me-3">Pedido #${pedido.id}</h5>
                                    <span class="badge bg-${pedido.estado.clase_css}">
                                        <i class="fas ${estadoIcon} me-1"></i>
                                        ${pedido.estado.mensaje}
                                    </span>
                                </div>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-calendar me-1"></i>
                                    ${pedido.fecha_formateada}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-box me-1"></i>
                                    ${pedido.cantidad_productos} producto${pedido.cantidad_productos !== 1 ? 's' : ''}
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <h4 class="text-primary mb-2">€${parseFloat(pedido.precio).toFixed(2)}</h4>
                                <button class="btn btn-outline-primary btn-sm" 
                                        onclick="verDetallePedido('${pedido.id}')">
                                    <i class="fas fa-eye me-1"></i>
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                        
                        <!-- Vista previa de productos -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <small class="text-muted d-block mb-2">Productos:</small>
                                <div class="d-flex flex-wrap gap-2">
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

        // Función global para ver detalles del pedido
        window.verDetallePedido = function(idPedido) {
            $.ajax({
                url: `/api/pedido/${idPedido}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        mostrarDetallePedido(response.pedido);
                    }
                },
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

        function mostrarDetallePedido(pedido) {
            const estadoClass = `text-${pedido.estado.clase_css}`;
            const estadoIcon = pedido.estado.icono;
            
            const contenido = `
                <div class="row mb-4">
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
                    <div class="col-md-6">
                        <h6>Información de Envío</h6>
                        ${pedido.direccion ? `<p><strong>Dirección:</strong> ${pedido.direccion}</p>` : ''}
                        ${pedido.ciudad ? `<p><strong>Ciudad:</strong> ${pedido.ciudad}</p>` : ''}
                        ${pedido.codigo_postal ? `<p><strong>Código Postal:</strong> ${pedido.codigo_postal}</p>` : ''}
                        ${pedido.metodo_pago ? `<p><strong>Método de Pago:</strong> ${pedido.metodo_pago}</p>` : ''}
                    </div>
                </div>
                
                <hr>
                
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
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3">Total</th>
                                <th>€${parseFloat(pedido.precio).toFixed(2)}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
            
            $('#contenido-detalle-pedido').html(contenido);
            $('#modal-detalle-pedido').modal('show');
        }
    });
    </script>
</body>
</html>