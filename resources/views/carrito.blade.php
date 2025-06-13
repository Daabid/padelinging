<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito de Compras</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@include('banner')
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header del Carrito -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Mi Carrito</h1>
                <p class="text-gray-600">Revisa tus productos antes de proceder al pago</p>
            </div>

            <!-- Alerta de mensajes dinámicos -->
            <div id="alert-container" class="hidden mb-6">
                <div id="alert-message" class="px-4 py-3 rounded"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Lista de Productos -->
                <div class="lg:col-span-2">
                    @if(isset($carrito) && count($carrito) > 0)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-xl font-semibold text-gray-900">
                                        Productos (<span id="total-items">{{ count($carrito) }}</span>)
                                    </h2>
                                    <form action="{{ route('carrito.vaciar') }}" method="POST" onsubmit="return confirm('¿Estás seguro de vaciar todo el carrito?')">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Vaciar Carrito
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div id="productos-container" class="divide-y divide-gray-200">
                                @foreach($carrito as $producto)
                                    <div class="p-6 flex items-center space-x-4" data-producto-id="{{ $producto['id'] }}">
                                        <!-- Imagen del Producto -->
                                        <div class="flex-shrink-0">
                                            @php
                                                $imagenUrl = asset('images/placeholder.jpg');
                                                if (isset($producto['imagen']) && !empty($producto['imagen'])) {
                                                    $rutaImagen = 'images/material/' . trim($producto['imagen'], '/') . '/Frente.jpg';
                                                    if (file_exists(public_path($rutaImagen))) {
                                                        $imagenUrl = asset($rutaImagen);
                                                    }
                                                }
                                            @endphp
                                            <img src="{{ $imagenUrl }}" 
                                                 alt="{{ $producto['nombre'] }}" 
                                                 class="w-20 h-20 object-cover rounded-lg border"
                                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                        </div>
                                        
                                        <!-- Información del Producto -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                                {{ $producto['nombre'] }}
                                            </h3>
                                            <p class="text-lg font-semibold text-gray-900 mt-2">
                                                €<span class="precio-unitario">{{ number_format($producto['precio'], 2) }}</span>
                                            </p>
                                        </div>
                                        
                                        <!-- Controles de Cantidad -->
                                        <div class="flex items-center space-x-3">
                                            <button type="button" 
                                                    onclick="cambiarCantidad({{ $producto['id'] }}, -1)"
                                                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            
                                            <input type="number" 
                                                   id="cantidad_{{ $producto['id'] }}"
                                                   value="{{ $producto['cantidad'] }}" 
                                                   min="1" 
                                                   max="{{ $producto['stock_disponible'] ?? 99 }}"
                                                   class="w-16 text-center border border-gray-300 rounded-md py-1"
                                                   onchange="actualizarCantidad({{ $producto['id'] }}, this.value)">
                                            
                                            <button type="button" 
                                                    onclick="cambiarCantidad({{ $producto['id'] }}, 1)"
                                                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Subtotal y Eliminar -->
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-gray-900">
                                                €<span class="subtotal-producto">{{ number_format($producto['precio'] * $producto['cantidad'], 2) }}</span>
                                            </p>
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
                        <!-- Carrito Vacío -->
                        <div class="bg-white rounded-lg shadow-md p-12 text-center">
                            <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H19M17 17v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v8.01"></path>
                            </svg>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Tu carrito está vacío</h2>
                            <p class="text-gray-600 mb-6">Explora nuestros productos y añade algunos a tu carrito</p>
                            <a href="/catalogo" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-150">
                                Continuar Comprando
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Resumen del Pedido -->
                @if(isset($carrito) && count($carrito) > 0)
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">Resumen del Pedido</h2>
                            
                            <!-- Detalles del Precio -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>€<span id="subtotal-display">{{ number_format($totalPrecio ?? 0, 2) }}</span></span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Envío</span>
                                    <span id="envio-display">
                                        @if(($totalPrecio ?? 0) >= 50)
                                            Gratis
                                        @else
                                            €5.99
                                        @endif
                                    </span>
                                </div>
                                <hr class="border-gray-200">
                                <div class="flex justify-between text-lg font-semibold text-gray-900">
                                    <span>Total</span>
                                    <span>€<span id="total-display">
                                        @php
                                            $subtotal = $totalPrecio ?? 0;
                                            $envio = $subtotal >= 50 ? 0 : 5.99;
                                            $total = $subtotal + $envio;
                                        @endphp
                                        {{ number_format($total, 2) }}
                                    </span></span>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="space-y-3">
                                <button type="button" 
                                        onclick="abrirModalCheckout()"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-150">
                                    Proceder al Pago
                                </button>
                                
                                <a href="/catalogo" 
                                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 px-4 rounded-lg transition duration-150 text-center block">
                                    Continuar Comprando
                                </a>
                            </div>

                            <!-- Garantías -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="space-y-3 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Envío gratuito desde €50
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Devoluciones en 30 días
                                    </div>
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

    <!-- Modal de Checkout -->
    <div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Finalizar Compra</h2>
                <button type="button" onclick="cerrarModalCheckout()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Formulario de Checkout -->
            <form id="checkout-form" onsubmit="procesarPedido(event)" class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Información de Envío -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Envío</h3>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                                    <input type="text" id="nombre" name="nombre" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">Apellidos *</label>
                                    <input type="text" id="apellidos" name="apellidos" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" id="email" name="email" required 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                                <input type="tel" id="telefono" name="telefono" required 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="dni" class="block text-sm font-medium text-gray-700 mb-1">DNI *</label>
                                <input type="text" id="dni" name="dni" required 
                                       placeholder="12345678A"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                                <input type="text" id="direccion" name="direccion" required 
                                       placeholder="Calle, Número, Piso..."
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                                    <input type="text" id="ciudad" name="ciudad" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="codigo_postal" class="block text-sm font-medium text-gray-700 mb-1">Código Postal *</label>
                                    <input type="text" id="codigo_postal" name="codigo_postal" required 
                                           pattern="[0-9]{5}" maxlength="5"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Pago -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Método de Pago</h3>
                        
                        <!-- Selector de Método de Pago -->
                        <div class="space-y-3 mb-6">
                            <label class="flex items-center">
                                <input type="radio" name="metodo_pago" value="tarjeta" checked 
                                       onchange="cambiarMetodoPago(this.value)"
                                       class="mr-3 text-blue-600">
                                <span>Tarjeta de Crédito/Débito</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="metodo_pago" value="paypal" 
                                       onchange="cambiarMetodoPago(this.value)"
                                       class="mr-3 text-blue-600">
                                <span>PayPal</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="metodo_pago" value="transferencia" 
                                       onchange="cambiarMetodoPago(this.value)"
                                       class="mr-3 text-blue-600">
                                <span>Transferencia Bancaria</span>
                            </label>
                        </div>

                        <!-- Formulario de Tarjeta -->
                        <div id="tarjeta-form" class="space-y-4">
                            <div>
                                <label for="numero_tarjeta" class="block text-sm font-medium text-gray-700 mb-1">Número de Tarjeta *</label>
                                <input type="text" id="numero_tarjeta" name="numero_tarjeta" 
                                       placeholder="1234 5678 9012 3456" maxlength="19"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="fecha_expiracion" class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                                    <input type="text" id="fecha_expiracion" name="fecha_expiracion" 
                                           placeholder="MM/AA" maxlength="5"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV *</label>
                                    <input type="text" id="cvv" name="cvv" 
                                           placeholder="123" maxlength="4"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label for="titular_tarjeta" class="block text-sm font-medium text-gray-700 mb-1">Titular *</label>
                                <input type="text" id="titular_tarjeta" name="titular_tarjeta" 
                                       placeholder="Nombre como aparece en la tarjeta"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Información otros métodos -->
                        <div id="paypal-form" class="hidden bg-blue-50 border border-blue-200 rounded-md p-4">
                            <p class="text-sm text-blue-800">Serás redirigido a PayPal para completar el pago.</p>
                        </div>

                        <div id="transferencia-form" class="hidden bg-gray-50 border border-gray-200 rounded-md p-4">
                            <p class="text-sm text-gray-800 mb-2">Recibirás los datos bancarios por email.</p>
                            <p class="text-xs text-gray-600">El pedido se procesará una vez confirmado el pago.</p>
                        </div>
                    </div>
                </div>

                <!-- Términos y Condiciones -->
                <div class="mt-6">
                    <label class="flex items-start">
                        <input type="checkbox" id="terminos" name="terminos" required 
                               class="mt-1 mr-3 text-blue-600">
                        <span class="text-sm text-gray-600">
                            Acepto los términos y condiciones y la política de privacidad
                        </span>
                    </label>
                </div>

                <!-- Botones del Modal -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="cerrarModalCheckout()" 
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                        Cancelar
                    </button>
                    <button type="submit" id="btn-procesar-pedido"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-150 disabled:opacity-50">
                        Confirmar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Función para mostrar alertas
        function mostrarAlerta(mensaje, tipo = 'success') {
            const container = document.getElementById('alert-container');
            const messageDiv = document.getElementById('alert-message');
            
            container.classList.remove('hidden');
            messageDiv.className = `px-4 py-3 rounded ${tipo === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'}`;
            
            if (mensaje.includes('\n')) {
                messageDiv.innerHTML = mensaje.split('\n').map(line => 
                    line.trim() ? `<div>${line}</div>` : ''
                ).join('');
            } else {
                messageDiv.textContent = mensaje;
            }
            
            setTimeout(() => {
                container.classList.add('hidden');
            }, tipo === 'error' ? 5000 : 3000);
        }

        // Funciones de carrito
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
                    document.querySelector(`[data-producto-id="${productoId}"]`).remove();
                    actualizarInterfaz();
                    mostrarAlerta('Producto eliminado');
                    
                    if (data.count === 0) {
                        setTimeout(() => window.location.reload(), 1500);
                    }
                } else {
                    mostrarAlerta(data.message || 'Error al eliminar', 'error');
                }
            });
        }

        function actualizarInterfaz() {
            fetch('{{ route("carrito.info") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-items').textContent = data.count || 0;
                document.getElementById('subtotal-display').textContent = data.total_precio;
                
                const subtotal = parseFloat(data.total_precio.replace(',', ''));
                const envio = subtotal >= 50 ? 0 : 5.99;
                const total = subtotal + envio;
                
                document.getElementById('envio-display').textContent = envio === 0 ? 'Gratis' : `€${envio.toFixed(2)}`;
                document.getElementById('total-display').textContent = total.toFixed(2);
            });
        }

        // Funciones del modal
        function abrirModalCheckout() {
            document.getElementById('checkout-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalCheckout() {
            document.getElementById('checkout-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function cambiarMetodoPago(metodo) {
            document.getElementById('tarjeta-form').classList.add('hidden');
            document.getElementById('paypal-form').classList.add('hidden');
            document.getElementById('transferencia-form').classList.add('hidden');
            
            if (metodo === 'tarjeta') {
                document.getElementById('tarjeta-form').classList.remove('hidden');
                ['numero_tarjeta', 'fecha_expiracion', 'cvv', 'titular_tarjeta'].forEach(field => {
                    document.getElementById(field).required = true;
                });
            } else {
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

        // 🔥 FUNCIÓN PRINCIPAL - PROCESAR PEDIDO CON REDIRECCIÓN A /CATALOGO
        function procesarPedido(event) {
            event.preventDefault();

            const btn = document.getElementById('btn-procesar-pedido');
            btn.disabled = true;
            btn.textContent = 'Procesando...';

            const formData = new FormData(event.target);
            const datos = Object.fromEntries(formData);

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
                    // ✅ ÉXITO: Limpiar carrito, mostrar mensaje y redirigir
                    
                    // 🧹 LIMPIAR CARRITO FRONTEND (NUEVO)
                    limpiarCarritoCompleto();
                    
                    mostrarAlerta(`¡Pedido confirmado! Número: #${data.numero_pedido}`);
                    cerrarModalCheckout();
                    
                    // 🎯 REDIRECCIÓN A /CATALOGO
                    setTimeout(() => {
                        window.location.href = '/catalogo';
                    }, 2000);
                    
                } else if (data.errors) {
                    // Error de validación
                    let errorMessage = 'Corrige los siguientes errores:\n';
                    Object.keys(data.errors).forEach(field => {
                        errorMessage += `• ${field}: ${data.errors[field].join(', ')}\n`;
                    });
                    mostrarAlerta(errorMessage, 'error');
                    
                    // Resaltar campos con errores
                    document.querySelectorAll('.border-red-500').forEach(el => {
                        el.classList.remove('border-red-500');
                        el.classList.add('border-gray-300');
                    });
                    
                    Object.keys(data.errors).forEach(field => {
                        const input = document.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('border-red-500');
                            input.classList.remove('border-gray-300');
                        }
                    });
                } else {
                    mostrarAlerta(data.message || 'Error al procesar el pedido', 'error');
                }
                
                // Rehabilitar botón si hay error
                if (!data.success) {
                    btn.disabled = false;
                    btn.textContent = 'Confirmar Pedido';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error de conexión', 'error');
                btn.disabled = false;
                btn.textContent = 'Confirmar Pedido';
            });
        }

        // 🧹 FUNCIÓN PARA LIMPIAR CARRITO COMPLETO (NUEVA)
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

            // Limpiar cualquier storage local si se usa
            if (typeof(Storage) !== "undefined") {
                localStorage.removeItem('carrito');
                sessionStorage.removeItem('carrito');
            }

            // Actualizar interfaz inmediatamente
            document.getElementById('total-items').textContent = '0';
            document.getElementById('subtotal-display').textContent = '0.00';
            document.getElementById('envio-display').textContent = 'Gratis';
            document.getElementById('total-display').textContent = '0.00';
        }

        // 🔧 FUNCIÓN DE EMERGENCIA PARA LIMPIAR CARRITO (NUEVA)
        function limpiarCarritoEmergencia() {
            if (confirm('¿Limpiar completamente el carrito? Esta acción no se puede deshacer.')) {
                limpiarCarritoCompleto();
                mostrarAlerta('Carrito limpiado correctamente');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-actualizar cantidad cuando se cambia manualmente
            document.querySelectorAll('input[type="number"]').forEach(input => {
                let timeout;
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    const productoId = this.id.replace('cantidad_', '');
                    timeout = setTimeout(() => {
                        if (this.value >= 1 && this.value <= 99) {
                            actualizarCantidad(productoId, parseInt(this.value));
                        }
                    }, 800);
                });
            });

            // Cerrar modal con Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') cerrarModalCheckout();
            });

            // Cerrar modal clickeando fuera
            document.getElementById('checkout-modal').addEventListener('click', function(e) {
                if (e.target === this) cerrarModalCheckout();
            });
        });
    </script>
</body>
</html>