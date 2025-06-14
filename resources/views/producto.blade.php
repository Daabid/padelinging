<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Título dinámico basado en el nombre del producto -->
    <title>{{ $producto->Nombre }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Token CSRF para protección contra ataques CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Framework CSS Tailwind para estilos utilitarios -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- === CONFIGURACIÓN PERSONALIZADA DE TAILWIND === -->
    <script>
        /**
         * Configuración extendida de Tailwind CSS
         * @description Añade colores y fuentes personalizadas al tema
         */
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#2a9d8f',        // Color principal verde azulado
                        'primary-dark': '#21867a',   // Variante más oscura para hover
                    },
                    fontFamily: {
                        'sans': ['Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        /* === ESTILOS PARA ESTADOS DEL BOTÓN DE CARRITO === */
        /* Texto mostrado durante el estado de carga */
        .btn-carrito.loading .loading-text {
            display: inline;
        }
        /* Oculta texto normal durante carga */
        .btn-carrito.loading .normal-text {
            display: none;
        }
        /* Estado inicial del texto de carga (oculto) */
        .loading-text {
            display: none;
        }
        
        /* === ANIMACIONES DE IMAGEN DE PRODUCTO === */
        /* Transiciones suaves para zoom y opacidad */
        .producto-imagen {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        /* Efecto zoom sutil al hacer hover */
        .producto-imagen:hover {
            transform: scale(1.01);
        }
        
        /* === ESTILOS PARA FLECHAS DEL CARRUSEL === */
        /* Flecha izquierda con gradiente de transparencia */
        .flecha {
            background: linear-gradient(to right, rgba(0,0,0,0.3), transparent);
        }
        .flecha:hover {
            background: linear-gradient(to right, rgba(0,0,0,0.5), transparent);
        }
        
        /* Flecha derecha con gradiente invertido */
        .flecha-derecha {
            background: linear-gradient(to left, rgba(0,0,0,0.3), transparent);
        }
        .flecha-derecha:hover {
            background: linear-gradient(to left, rgba(0,0,0,0.5), transparent);
        }
        
        /* === SISTEMA DE NOTIFICACIONES === */
        /* Notificaciones que se deslizzan desde la derecha */
        .notification {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        .notification.show {
            transform: translateX(0);
        }
        
        /* === ESTILOS PARA THUMBNAILS DEL MODAL === */
        /* Estilo para thumbnail activo en el modal */
        .thumb.active {
            border-color: #2a9d8f;
            opacity: 1;
        }
    </style>
</head>

<!-- Incluye el banner/navegación del sitio -->
@include('banner')

<body class="font-sans bg-gray-100 m-0 p-0">
    
    <!-- === CONTENEDOR PRINCIPAL === -->
    <div class="p-5 md:p-20 flex justify-center min-h-screen">
        <!-- Layout responsivo: columna en móvil, fila en desktop -->
        <div class="flex bg-white rounded-2xl shadow-lg max-w-4xl w-full p-8 gap-8 items-start my-10 flex-col lg:flex-row">
            
            <!-- === SECCIÓN DE IMÁGENES (CARRUSEL) === -->
            <div class="relative w-full lg:w-1/2 lg:max-w-lg">
                <!-- Botón flecha izquierda para navegación -->
                <button class="flecha absolute top-0 bottom-0 left-0 w-12 border-none cursor-pointer text-white text-3xl font-bold flex items-center justify-center transition-all duration-300 p-0 rounded-l-xl z-10" id="prevBtn">&#8249;</button>
                
                <!-- Imagen principal del producto -->
                <img id="imagenProducto" 
                     src="{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}" 
                     alt="{{ $producto->Nombre }}" 
                     class="producto-imagen w-full h-auto rounded-xl bg-gray-50 object-contain shadow-sm select-none cursor-pointer" />
                
                <!-- Botón flecha derecha para navegación -->
                <button class="flecha-derecha absolute top-0 bottom-0 right-0 w-12 border-none cursor-pointer text-white text-3xl font-bold flex items-center justify-center transition-all duration-300 p-0 rounded-r-xl z-10" id="nextBtn">&#8250;</button>
            </div>

            <!-- === INFORMACIÓN DEL PRODUCTO === -->
            <div class="w-full lg:w-1/2 flex flex-col justify-start">
                <!-- Título del producto -->
                <h1 class="m-0 mb-4 text-gray-800 text-3xl font-normal">{{ $producto->Nombre }}</h1>

                <!-- === PRECIO Y BOTÓN DE CARRITO === -->
                <div class="flex justify-start items-center mb-5 flex-wrap gap-5">
                    <!-- Precio del producto -->
                    <div>
                        <div class="text-primary font-bold text-2xl m-0">{{ number_format($producto->Precio, 2) }}€</div>
                    </div>

                    <!-- Botón para añadir al carrito -->
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

                <!-- === PROCESAMIENTO DE DESCRIPCIÓN CON PHP === -->
                @php
                    /**
                     * Procesa la descripción del producto para crear HTML estructurado
                     * @description Convierte texto plano en HTML con listas y párrafos
                     * - Detecta líneas que terminan en ':' como títulos
                     * - Convierte líneas con patrones específicos en elementos de lista
                     * - Mantiene párrafos normales para el resto del texto
                     */
                    $desc = e($producto->Descripción);              // Escapa HTML por seguridad
                    $lineas = explode("\n", $desc);                // Divide en líneas
                    $htmlDescripcion = '';                          // HTML resultante
                    $enLista = false;                               // Estado de lista activa
                    
                    foreach ($lineas as $linea) {
                        $linea = trim($linea);                      // Elimina espacios
                        
                        if ($linea === '') {
                            // Línea vacía: cierra lista si está activa
                            if ($enLista) {
                                $htmlDescripcion .= '</ul>';
                                $enLista = false;
                            }
                        } elseif (str_ends_with($linea, ':')) {
                            // Línea con ':' al final: es un título
                            if ($enLista) {
                                $htmlDescripcion .= '</ul>';
                                $enLista = false;
                            }
                            $htmlDescripcion .= '<p class="font-bold mt-4 mb-2 text-gray-800">' . $linea . '</p>';
                        } elseif (preg_match('/^(Peso|Forma|Balance|Núcleo|Superficie|Perfil|Grip|Taladrado):/', $linea)) {
                            // Línea con patrón específico: elemento de lista
                            if (!$enLista) {
                                $htmlDescripcion .= '<ul class="list-disc ml-5 mb-4 p-0 text-gray-600">';
                                $enLista = true;
                            }
                            $htmlDescripcion .= '<li>' . $linea . '</li>';
                        } else {
                            // Línea normal: párrafo
                            if ($enLista) {
                                $htmlDescripcion .= '</ul>';
                                $enLista = false;
                            }
                            $htmlDescripcion .= '<p class="m-0 mb-2">' . $linea . '</p>';
                        }
                    }
                    
                    // Cierra lista si quedó abierta
                    if ($enLista) {
                        $htmlDescripcion .= '</ul>';
                    }
                @endphp

                <!-- === DESCRIPCIÓN PROCESADA === -->
                <!-- Muestra la descripción convertida a HTML estructurado -->
                <div class="text-gray-600 text-base leading-6 mb-8">
                    {!! $htmlDescripcion !!}
                </div>

                <!-- === ENLACE DE NAVEGACIÓN === -->
                <!-- Botón para volver a la página anterior -->
                <a href="{{ url()->previous() }}" class="mt-8 inline-block no-underline text-primary font-semibold transition-colors duration-300 hover:text-primary-dark">← Volver al catálogo</a>
            </div>
        </div>
    </div>

    <!-- === MODAL PARA VISTA AMPLIADA === -->
    <!-- Modal que se abre al hacer clic en la imagen principal -->
    <div id="modal" class="hidden fixed z-50 pt-15 left-0 top-0 w-full h-full overflow-auto bg-black bg-opacity-80 text-center">
        <!-- Botón para cerrar el modal -->
        <span class="absolute top-4 right-9 text-white text-4xl font-bold cursor-pointer transition-all duration-300 hover:text-gray-400" id="cerrarModal">&times;</span>
        
        <!-- Imagen ampliada en el modal -->
        <img class="mx-auto max-w-[90%] max-h-[70vh] rounded-lg shadow-lg shadow-black/50" id="modalImagen">
        
        <!-- === THUMBNAILS DE NAVEGACIÓN === -->
        <!-- Miniaturas para navegar entre las diferentes vistas del producto -->
        <div class="mt-5 flex justify-center gap-2 flex-wrap" id="modalThumbnails">
            <img src="{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}" 
                 class="thumb w-20 h-auto border-2 border-transparent rounded-md cursor-pointer opacity-70 transition-all duration-300 hover:border-primary hover:opacity-100" 
                 data-index="0">
            <img src="{{ asset('/images/material/' . $producto->URL . 'Lateral1.jpg') }}" 
                 class="thumb w-20 h-auto border-2 border-transparent rounded-md cursor-pointer opacity-70 transition-all duration-300 hover:border-primary hover:opacity-100" 
                 data-index="1">
            <img src="{{ asset('/images/material/' . $producto->URL . 'Lateral2.jpg') }}" 
                 class="thumb w-20 h-auto border-2 border-transparent rounded-md cursor-pointer opacity-70 transition-all duration-300 hover:border-primary hover:opacity-100" 
                 data-index="2">
        </div>
    </div>

    <script>
        /**
         * === SISTEMA DE NOTIFICACIONES ===
         * @description Crea y muestra notificaciones temporales con diferentes tipos
         */
        
        /**
         * Muestra una notificación temporal en la esquina superior derecha
         * @param {string} mensaje - Texto a mostrar en la notificación
         * @param {string} [tipo='success'] - Tipo: 'success', 'error', 'warning'
         */
        function mostrarNotificacion(mensaje, tipo = 'success') {
            // Crea el elemento de notificación
            const notification = document.createElement('div');
            let bgColor;
            
            // Determina el color según el tipo
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
            
            // Configura estilos y contenido
            notification.className = `notification fixed top-20 right-5 py-4 px-5 rounded-md text-white font-bold z-50 ${bgColor}`;
            notification.textContent = mensaje;
            document.body.appendChild(notification);
            
            // === ANIMACIÓN DE ENTRADA ===
            setTimeout(() => notification.classList.add('show'), 100);
            
            // === AUTO-ELIMINACIÓN ===
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
         * === SISTEMA DE CARRUSEL DE IMÁGENES ===
         * @description Maneja la navegación entre las diferentes vistas del producto
         */
        
        // Referencias a elementos DOM
        const imagenProducto = document.getElementById('imagenProducto');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        /**
         * Array con las rutas de las imágenes del producto
         * @type {string[]}
         */
        const imagenes = [
            "{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}",
            "{{ asset('/images/material/' . $producto->URL . 'Lateral1.jpg') }}",
            "{{ asset('/images/material/' . $producto->URL . 'Lateral2.jpg') }}"
        ];

        /** @type {number} Índice de la imagen actualmente mostrada */
        let indiceActual = 0;

        /**
         * Cambia la imagen mostrada con efecto de transición
         * @param {number} indice - Índice de la imagen a mostrar
         */
        function mostrarImagen(indice) {
            // Efecto fade out
            imagenProducto.style.opacity = 0;

            // Cambia la imagen después del fade out
            setTimeout(() => {
                imagenProducto.src = imagenes[indice];
                imagenProducto.style.opacity = 1; // Fade in
            }, 300);
        }

        /**
         * === EVENT LISTENERS PARA NAVEGACIÓN ===
         */
        
        // Botón anterior: navega a la imagen previa
        prevBtn.addEventListener('click', () => {
            indiceActual = (indiceActual - 1 + imagenes.length) % imagenes.length;
            mostrarImagen(indiceActual);
        });

        // Botón siguiente: navega a la siguiente imagen
        nextBtn.addEventListener('click', () => {
            indiceActual = (indiceActual + 1) % imagenes.length;
            mostrarImagen(indiceActual);
        });

        /**
         * === FUNCIONALIDAD DEL MODAL ===
         * @description Maneja la apertura y navegación del modal de imagen ampliada
         */
        
        // Referencias a elementos del modal
        const modal = document.getElementById("modal");
        const modalImg = document.getElementById("modalImagen");
        const cerrarModal = document.getElementById("cerrarModal");
        const thumbnails = document.querySelectorAll(".thumb");

        /**
         * Abre el modal al hacer clic en la imagen principal
         */
        imagenProducto.addEventListener("click", () => {
            modal.classList.remove("hidden");
            modal.style.display = "block";
            actualizarModalImagen(indiceActual);
        });

        /**
         * Cierra el modal
         */
        cerrarModal.onclick = () => {
            modal.style.display = "none";
            modal.classList.add("hidden");
        };

        /**
         * Event listeners para thumbnails en el modal
         */
        thumbnails.forEach(thumb => {
            thumb.addEventListener("click", () => {
                indiceActual = parseInt(thumb.dataset.index);
                actualizarModalImagen(indiceActual);
            });
        });

        /**
         * Actualiza la imagen mostrada en el modal
         * @param {number} index - Índice de la imagen a mostrar
         */
        function actualizarModalImagen(index) {
            modalImg.src = imagenes[index];
            // Actualiza el estado visual de los thumbnails
            thumbnails.forEach(t => t.classList.remove("active"));
            thumbnails[index].classList.add("active");
        }

        /**
         * === NAVEGACIÓN CON TECLADO ===
         * @description Permite controlar el modal con teclas del teclado
         */
        document.addEventListener("keydown", (e) => {
            if (modal.style.display === "block") {
                if (e.key === "ArrowLeft") {
                    // Flecha izquierda: imagen anterior
                    indiceActual = (indiceActual - 1 + imagenes.length) % imagenes.length;
                    actualizarModalImagen(indiceActual);
                } else if (e.key === "ArrowRight") {
                    // Flecha derecha: imagen siguiente
                    indiceActual = (indiceActual + 1) % imagenes.length;
                    actualizarModalImagen(indiceActual);
                } else if (e.key === "Escape") {
                    // Escape: cerrar modal
                    modal.style.display = "none";
                    modal.classList.add("hidden");
                }
            }
        });

        /**
         * === FUNCIONALIDAD DEL CARRITO ===
         * @description Maneja la adición de productos al carrito con autenticación
         */
        document.addEventListener('DOMContentLoaded', function() {
            const btnCarrito = document.getElementById('btnAgregarCarrito');
            
            if (btnCarrito) {
                /**
                 * Event listener para el botón de añadir al carrito
                 */
                btnCarrito.addEventListener('click', function(event) {
                    event.preventDefault();
                    
                    // Verifica si el botón está deshabilitado
                    if (this.disabled) {
                        return;
                    }
                    
                    // Obtiene datos del producto
                    const productoId = this.dataset.productoId;
                    const stock = parseInt(this.dataset.stock) || 0;
                    
                    // === VALIDACIÓN DE STOCK ===
                    if (stock <= 0) {
                        mostrarNotificacion('Producto sin stock', 'error');
                        return;
                    }
                    
                    // === ESTADO DE CARGA ===
                    // Deshabilita el botón y muestra estado de carga
                    this.disabled = true;
                    this.classList.add('loading');
                    
                    // === OBTENCIÓN DEL TOKEN CSRF ===
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        mostrarNotificacion('Error de configuración', 'error');
                        this.disabled = false;
                        this.classList.remove('loading');
                        return;
                    }

                    const token = csrfToken.getAttribute('content');

                    // === PREPARACIÓN DE DATOS ===
                    const formData = new FormData();
                    formData.append('producto_id', productoId);
                    formData.append('cantidad', '1');
                    formData.append('_token', token);
                    
                    /**
                     * === PETICIÓN AJAX AL SERVIDOR ===
                     * @description Envía petición POST para añadir producto al carrito
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
                        /**
                         * === MANEJO DE CÓDIGOS DE ESTADO HTTP ===
                         */
                        if (response.status === 401) {
                            // Error de autenticación
                            return response.json().then(data => {
                                throw new Error(JSON.stringify({...data, status: 401}));
                            });
                        }
                        
                        if (!response.ok) {
                            // Error HTTP general
                            return response.text().then(text => {
                                throw new Error(`HTTP error! status: ${response.status} - ${text}`);
                            });
                        }
                        
                        // === VALIDACIÓN DE RESPUESTA JSON ===
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
                        /**
                         * === MANEJO DE RESPUESTA EXITOSA ===
                         */
                        if (data.success) {
                            mostrarNotificacion(data.message || 'Producto añadido al carrito', 'success');
                            
                            /**
                             * === ACTUALIZACIÓN DEL CONTADOR DEL CARRITO ===
                             * @description Actualiza el contador visual en la interfaz
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
                            /**
                             * === MANEJO DE ERRORES DEL SERVIDOR ===
                             */
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
                        /**
                         * === MANEJO DE ERRORES DE RED Y PARSING ===
                         */
                        console.error('Fetch error:', error);
                        
                        try {
                            // Intenta parsear el error como JSON
                            const errorData = JSON.parse(error.message);
                            if (errorData.status === 401 && errorData.type === 'auth_required') {
                                // Error específico de autenticación
                                mostrarNotificacion('Debes iniciar sesión para añadir productos al carrito', 'warning');
                                
                                // Redirige al login después del mensaje
                                setTimeout(() => {
                                    window.location.href = errorData.redirect || '/login';
                                }, 2000);
                            } else {
                                mostrarNotificacion(errorData.message || 'Error al añadir al carrito', 'error');
                            }
                        } catch (parseError) {
                            /**
                             * === MANEJO DE ERRORES GENÉRICOS ===
                             * @description Categoriza diferentes tipos de error
                             */
                            let mensajeError = 'Error de conexión';
                            
                            // Errores específicos de stock
                            if (error.message && (
                                error.message.toLowerCase().includes('stock') || 
                                error.message.toLowerCase().includes('disponible') ||
                                error.message.toLowerCase().includes('suficiente') ||
                                error.message.includes('422') ||
                                error.message.includes('400')
                            )) {
                                mensajeError = 'No se ha podido añadir, stock insuficiente';
                            } else if (error.message) {
                                // Otros tipos de error
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
                        /**
                         * === RESTAURACIÓN DEL ESTADO DEL BOTÓN ===
                         * @description Siempre se ejecuta, exitoso o con error
                         */
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