<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $producto->Nombre }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#2a9d8f',
                        'primary-dark': '#21867a',
                    },
                    fontFamily: {
                        'sans': ['Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .btn-carrito.loading .loading-text {
            display: inline;
        }
        .btn-carrito.loading .normal-text {
            display: none;
        }
        .loading-text {
            display: none;
        }
        .producto-imagen {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .producto-imagen:hover {
            transform: scale(1.01);
        }
        .flecha {
            background: linear-gradient(to right, rgba(0,0,0,0.3), transparent);
        }
        .flecha:hover {
            background: linear-gradient(to right, rgba(0,0,0,0.5), transparent);
        }
        .flecha-derecha {
            background: linear-gradient(to left, rgba(0,0,0,0.3), transparent);
        }
        .flecha-derecha:hover {
            background: linear-gradient(to left, rgba(0,0,0,0.5), transparent);
        }
        .notification {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        .notification.show {
            transform: translateX(0);
        }
        .thumb.active {
            border-color: #2a9d8f;
            opacity: 1;
        }
    </style>
</head>

@include('banner')

<body class="font-sans bg-gray-100 m-0 p-0">
    
    <div class="p-5 md:p-20 flex justify-center min-h-screen">
        <div class="flex bg-white rounded-2xl shadow-lg max-w-4xl w-full p-8 gap-8 items-start my-10 flex-col lg:flex-row">
            <!-- Carrusel de imágenes -->
            <div class="relative w-full lg:w-1/2 lg:max-w-lg">
                <button class="flecha absolute top-0 bottom-0 left-0 w-12 border-none cursor-pointer text-white text-3xl font-bold flex items-center justify-center transition-all duration-300 p-0 rounded-l-xl z-10" id="prevBtn">&#8249;</button>
                
                <img id="imagenProducto" 
                     src="{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}" 
                     alt="{{ $producto->Nombre }}" 
                     class="producto-imagen w-full h-auto rounded-xl bg-gray-50 object-contain shadow-sm select-none cursor-pointer" />
                
                <button class="flecha-derecha absolute top-0 bottom-0 right-0 w-12 border-none cursor-pointer text-white text-3xl font-bold flex items-center justify-center transition-all duration-300 p-0 rounded-r-xl z-10" id="nextBtn">&#8250;</button>
            </div>

            <!-- Información del producto -->
            <div class="w-full lg:w-1/2 flex flex-col justify-start">
                <h1 class="m-0 mb-4 text-gray-800 text-3xl font-normal">{{ $producto->Nombre }}</h1>

                <div class="flex justify-start items-center mb-5 flex-wrap gap-5">
                    <div>
                        <div class="text-primary font-bold text-2xl m-0">{{ number_format($producto->Precio, 2) }}€</div>
                        
                    </div>

                    <button type="button" 
                            class="btn-carrito bg-primary text-white border-none py-3 px-6 text-base rounded-lg cursor-pointer transition-colors duration-300 whitespace-nowrap relative overflow-hidden hover:bg-primary-dark disabled:bg-gray-400 disabled:cursor-not-allowed disabled:hover:bg-gray-400 w-full md:w-auto"
                            id="btnAgregarCarrito"
                            data-producto-id="{{ $producto->IDProducto }}"
                            data-stock="{{ $producto->Stock ?? 0 }}"
                            {{ ($producto->Stock ?? 0) <= 0 ? 'disabled' : '' }}>
                        <span class="normal-text">Añadir al carrito</span>
                        <span class="loading-text">Agregando...</span>
                    </button>
                </div>

                @php
                    $desc = e($producto->Descripción);
                    $lineas = explode("\n", $desc);
                    $htmlDescripcion = '';
                    $enLista = false;
                    foreach ($lineas as $linea) {
                        $linea = trim($linea);
                        if ($linea === '') {
                            if ($enLista) {
                                $htmlDescripcion .= '</ul>';
                                $enLista = false;
                            }
                        } elseif (str_ends_with($linea, ':')) {
                            if ($enLista) {
                                $htmlDescripcion .= '</ul>';
                                $enLista = false;
                            }
                            $htmlDescripcion .= '<p class="font-bold mt-4 mb-2 text-gray-800">' . $linea . '</p>';
                        } elseif (preg_match('/^(Peso|Forma|Balance|Núcleo|Superficie|Perfil|Grip|Taladrado):/', $linea)) {
                            if (!$enLista) {
                                $htmlDescripcion .= '<ul class="list-disc ml-5 mb-4 p-0 text-gray-600">';
                                $enLista = true;
                            }
                            $htmlDescripcion .= '<li>' . $linea . '</li>';
                        } else {
                            if ($enLista) {
                                $htmlDescripcion .= '</ul>';
                                $enLista = false;
                            }
                            $htmlDescripcion .= '<p class="m-0 mb-2">' . $linea . '</p>';
                        }
                    }
                    if ($enLista) {
                        $htmlDescripcion .= '</ul>';
                    }
                @endphp

                <div class="text-gray-600 text-base leading-6 mb-8">
                    {!! $htmlDescripcion !!}
                </div>

                <a href="{{ url()->previous() }}" class="mt-8 inline-block no-underline text-primary font-semibold transition-colors duration-300 hover:text-primary-dark">← Volver al catálogo</a>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="hidden fixed z-50 pt-15 left-0 top-0 w-full h-full overflow-auto bg-black bg-opacity-80 text-center">
        <span class="absolute top-4 right-9 text-white text-4xl font-bold cursor-pointer transition-all duration-300 hover:text-gray-400" id="cerrarModal">&times;</span>
        <img class="mx-auto max-w-[90%] max-h-[70vh] rounded-lg shadow-lg shadow-black/50" id="modalImagen">
        <div class="mt-5 flex justify-center gap-2 flex-wrap" id="modalThumbnails">
            <img src="{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}" class="thumb w-20 h-auto border-2 border-transparent rounded-md cursor-pointer opacity-70 transition-all duration-300 hover:border-primary hover:opacity-100" data-index="0">
            <img src="{{ asset('/images/material/' . $producto->URL . 'Lateral1.jpg') }}" class="thumb w-20 h-auto border-2 border-transparent rounded-md cursor-pointer opacity-70 transition-all duration-300 hover:border-primary hover:opacity-100" data-index="1">
            <img src="{{ asset('/images/material/' . $producto->URL . 'Lateral2.jpg') }}" class="thumb w-20 h-auto border-2 border-transparent rounded-md cursor-pointer opacity-70 transition-all duration-300 hover:border-primary hover:opacity-100" data-index="2">
        </div>
    </div>

    <script>
        // Función para mostrar notificaciones mejorada con soporte para warning
        function mostrarNotificacion(mensaje, tipo = 'success') {
            const notification = document.createElement('div');
            let bgColor;
            
            switch(tipo) {
                case 'success':
                    bgColor = 'bg-primary';
                    break;
                case 'error':
                    bgColor = 'bg-red-600';
                    break;
                case 'warning':
                    bgColor = 'bg-yellow-500';
                    break;
                default:
                    bgColor = 'bg-primary';
            }
            
            notification.className = `notification fixed top-20 right-5 py-4 px-5 rounded-md text-white font-bold z-50 ${bgColor}`;
            notification.textContent = mensaje;
            document.body.appendChild(notification);
            
            // Mostrar notificación
            setTimeout(() => notification.classList.add('show'), 100);
            
            // Ocultar y eliminar notificación después de 5 segundos
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        // Carrusel de imágenes
        const imagenProducto = document.getElementById('imagenProducto');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        const imagenes = [
            "{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}",
            "{{ asset('/images/material/' . $producto->URL . 'Lateral1.jpg') }}",
            "{{ asset('/images/material/' . $producto->URL . 'Lateral2.jpg') }}"
        ];

        let indiceActual = 0;

        function mostrarImagen(indice) {
            imagenProducto.style.opacity = 0;

            setTimeout(() => {
                imagenProducto.src = imagenes[indice];
                imagenProducto.style.opacity = 1;
            }, 300);
        }

        prevBtn.addEventListener('click', () => {
            indiceActual = (indiceActual - 1 + imagenes.length) % imagenes.length;
            mostrarImagen(indiceActual);
        });

        nextBtn.addEventListener('click', () => {
            indiceActual = (indiceActual + 1) % imagenes.length;
            mostrarImagen(indiceActual);
        });

        // Modal
        const modal = document.getElementById("modal");
        const modalImg = document.getElementById("modalImagen");
        const cerrarModal = document.getElementById("cerrarModal");
        const thumbnails = document.querySelectorAll(".thumb");

        imagenProducto.addEventListener("click", () => {
            modal.classList.remove("hidden");
            modal.style.display = "block";
            actualizarModalImagen(indiceActual);
        });

        cerrarModal.onclick = () => {
            modal.style.display = "none";
            modal.classList.add("hidden");
        };

        thumbnails.forEach(thumb => {
            thumb.addEventListener("click", () => {
                indiceActual = parseInt(thumb.dataset.index);
                actualizarModalImagen(indiceActual);
            });
        });

        function actualizarModalImagen(index) {
            modalImg.src = imagenes[index];
            thumbnails.forEach(t => t.classList.remove("active"));
            thumbnails[index].classList.add("active");
        }

        document.addEventListener("keydown", (e) => {
            if (modal.style.display === "block") {
                if (e.key === "ArrowLeft") {
                    indiceActual = (indiceActual - 1 + imagenes.length) % imagenes.length;
                    actualizarModalImagen(indiceActual);
                } else if (e.key === "ArrowRight") {
                    indiceActual = (indiceActual + 1) % imagenes.length;
                    actualizarModalImagen(indiceActual);
                } else if (e.key === "Escape") {
                    modal.style.display = "none";
                    modal.classList.add("hidden");
                }
            }
        });

        // Funcionalidad del carrito con manejo de autenticación
        document.addEventListener('DOMContentLoaded', function() {
            const btnCarrito = document.getElementById('btnAgregarCarrito');
            
            if (btnCarrito) {
                btnCarrito.addEventListener('click', function(event) {
                    event.preventDefault();
                    
                    if (this.disabled) {
                        return;
                    }
                    
                    const productoId = this.dataset.productoId;
                    const stock = parseInt(this.dataset.stock) || 0;
                    
                    // Verificar stock
                    if (stock <= 0) {
                        mostrarNotificacion('Producto sin stock', 'error');
                        return;
                    }
                    
                    // Deshabilitar botón y mostrar estado de carga
                    this.disabled = true;
                    this.classList.add('loading');
                    
                    // Obtener token CSRF
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        mostrarNotificacion('Error de configuración', 'error');
                        this.disabled = false;
                        this.classList.remove('loading');
                        return;
                    }

                    const token = csrfToken.getAttribute('content');

                    // Crear FormData para enviar datos
                    const formData = new FormData();
                    formData.append('producto_id', productoId);
                    formData.append('cantidad', '1');
                    formData.append('_token', token);
                    
                    // Realizar petición AJAX con manejo de autenticación
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
                            
                            // Actualizar contador del carrito si existe
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
                            // Manejar diferentes tipos de errores
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
                                
                                // Redirigir al login después de mostrar el mensaje
                                setTimeout(() => {
                                    window.location.href = errorData.redirect || '/login';
                                }, 2000);
                            } else {
                                mostrarNotificacion(errorData.message || 'Error al añadir al carrito', 'error');
                            }
                        } catch (parseError) {
                            // Error genérico si no se puede parsear
                            let mensajeError = 'Error de conexión';
                            
                            // Manejar errores específicos de stock
                            if (error.message && (
                                error.message.toLowerCase().includes('stock') || 
                                error.message.toLowerCase().includes('disponible') ||
                                error.message.toLowerCase().includes('suficiente') ||
                                error.message.includes('422') ||
                                error.message.includes('400')
                            )) {
                                mensajeError = 'No se ha podido añadir, stock insuficiente';
                            } else if (error.message) {
                                if (error.message.includes('HTTP error')) {
                                    mensajeError = 'Error del servidor, inténtalo de nuevo';
                                } else if (error.message.includes('JSON')) {
                                    mensajeError = 'Error de comunicación con el servidor';
                                } else {
                                    mensajeError = 'No se pudo completar la operación';
                                }
                            }
                            
                            mostrarNotificacion(mensajeError, 'error');
                        }
                    })
                    .finally(() => {
                        // Restaurar estado del botón
                        setTimeout(() => {
                            this.disabled = false;
                            this.classList.remove('loading');
                        }, 1000);
                    });
                });
            }
        });
    </script>

</body>
</html>