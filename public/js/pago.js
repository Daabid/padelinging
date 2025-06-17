 const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const response = fetch('/api/Reserva', {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({
        Usuario: "41514002Z",
        Pista: 1,
        Alquiler: 1,
        FInicio: "2025-06-20 17:00:00",
        FFinal: "2025-06-20 18:00:00"
    })
});
console.log(response);

/**
 * Script para gestión de formulario de pago - Padelinging
 * Maneja validaciones, formateo de campos y procesamiento de pagos
 */

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('paymentForm');
    const numeroTarjeta = document.getElementById('numeroTarjeta');
    const fechaVencimiento = document.getElementById('fechaVencimiento');
    const cvv = document.getElementById('cvv');
    const cardIcon = document.getElementById('cardIcon');

    // Formatear número de tarjeta
    numeroTarjeta.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        if (formattedValue.length > 19) formattedValue = formattedValue.slice(0, 19);
        e.target.value = formattedValue;

        // Detectar tipo de tarjeta
        if (value.startsWith('4')) {
            cardIcon.textContent = '💳'; // Visa
        } else if (value.startsWith('5')) {
            cardIcon.textContent = '💳'; // Mastercard
        } else {
            cardIcon.textContent = '💳';
        }
    });

    // Formatear fecha de vencimiento
    fechaVencimiento.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });

    // Solo números en CVV
    cvv.addEventListener('input', function (e) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });

    // Validación del formulario
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            const errorMessage = field.parentNode.querySelector('.errorMessage');
            if (errorMessage) errorMessage.remove();

            field.classList.remove('error');

            if (!field.value.trim()) {
                field.classList.add('error');
                showError(field, 'Este campo es obligatorio');
                isValid = false;
            }
        });

        // Validaciones específicas
        if (numeroTarjeta.value.replace(/\s/g, '').length < 16) {
            showError(numeroTarjeta, 'El número de tarjeta debe tener 16 dígitos');
            isValid = false;
        }

        if (fechaVencimiento.value.length < 5) {
            showError(fechaVencimiento, 'Formato: MM/AA');
            isValid = false;
        }

        if (cvv.value.length < 3) {
            showError(cvv, 'El CVV debe tener 3 o 4 dígitos');
            isValid = false;
        }

        if (isValid) {
            procesarPago();
        }
    });

    /**
     * Función para mostrar errores en los campos
     * @param {HTMLElement} field - Campo con error
     * @param {string} message - Mensaje de error
     */
    function showError(field, message) {
        field.classList.add('error');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'errorMessage';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    /**
     * Función para crear alquiler usando POST
     * @param {string} usuario - DNI del usuario
     * @param {string} fechaInicio - Fecha inicio (YYYY-MM-DD HH:mm:ss)
     * @param {string} fechaFinal - Fecha final (YYYY-MM-DD HH:mm:ss)
     * @param {number} precio - Precio del alquiler
     * @returns {Promise} Promesa con los datos del alquiler creado
     */
    async function crearAlquiler(usuario, fechaInicio, fechaFinal, precio) {
        try {
            // Obtener token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                throw new Error('Token CSRF no encontrado. Asegúrate de tener <meta name="csrf-token" content="{{ csrf_token() }}"> en el <head>');
            }

            console.log('Creando alquiler:', { usuario, fechaInicio, fechaFinal, precio });

            const response = await fetch('api/Alquiler', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    Usuario: usuario,
                    FInicio: fechaInicio,
                    FFinal: fechaFinal,
                    Precio: precio
                })
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`HTTP ${response.status}: ${errorText}`);
            }

            const data = await response.json();
            console.log('✅ Alquiler creado exitosamente:', data);

            return data;

        } catch (error) {
            console.error('❌ Error creando alquiler:', error);
            throw error;
        }
    }

    /**
     * Función para formatear fecha y hora
     * @param {string} fecha - Fecha (YYYY-MM-DD)
     * @param {string} hora - Hora (HH:mm:ss)
     * @returns {string} Fecha formateada (YYYY-MM-DD HH:mm:ss)
     */
    function formatearFechaLocal(fecha, hora) {
        let fechaCompleta = new Date(`${fecha} ${hora}`);
        let año = fechaCompleta.getFullYear();
        let mes = String(fechaCompleta.getMonth() + 1).padStart(2, '0');
        let dia = String(fechaCompleta.getDate()).padStart(2, '0');
        let horas = String(fechaCompleta.getHours()).padStart(2, '0');
        let minutos = String(fechaCompleta.getMinutes()).padStart(2, '0');
        let segundos = String(fechaCompleta.getSeconds()).padStart(2, '0');

        return `${año}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
    }

    /**
     * Función principal para procesar el pago
     */
    async function procesarPago() {
        const payButton = document.getElementById('payButton');
        payButton.disabled = true;
        payButton.textContent = 'Procesando...';

        try {
            // Obtener reservas del localStorage
            if (!localStorage["reservas"]) {
                throw new Error('No hay reservas para procesar');
            }

            const reservas = JSON.parse(localStorage["reservas"]);
            console.log('Procesando reservas:', reservas);

            // Procesar cada reserva
            for (const reserva of reservas) {
                const user = '41514002Z'; // TODO: Obtener del usuario autenticado
                const fechaInicio = formatearFechaLocal(reserva["fecha"], reserva["hora_inicio"]);
                const fechaFinal = formatearFechaLocal(reserva["fecha"], reserva["hora_fin"]);

                console.log(`🔄 Procesando reserva: ${reserva["pista"]} - ${fechaInicio} a ${fechaFinal} - ${reserva["precio"]}€`);

                // Crear alquiler
                const alquilerData = await crearAlquiler(user, fechaInicio, fechaFinal, reserva["precio"]);

                if (!alquilerData.alquiler || !alquilerData.alquiler.ID) {
                    throw new Error('No se recibió ID de alquiler válido');
                }

                const alquilerID = alquilerData.alquiler.ID;
                console.log(`✅ Alquiler creado con ID: ${alquilerID}`);

                // Delay antes de crear la reserva
                await new Promise(resolve => setTimeout(resolve, 100));

                // Crear reserva
                console.log(`🔄 Creando reserva...`);
                //const reservaResponse = await fetch(`/reserva/pago/${user}&&${reserva["pista"]}&&${alquilerID}&&${fechaInicio}&&${fechaFinal}`);
                const reservaData = await reservaResponse.json();

                console.log("✅ Reserva creada:", reservaData);
            }

            // Éxito - mostrar mensaje y redirigir
            alert('¡Pago realizado con éxito! Te hemos enviado la confirmación por email.');
            localStorage.removeItem('reservas'); // Limpiar reservas

        } catch (error) {
            console.error('❌ Error procesando pago:', error);
            alert('Error procesando el pago: ' + error.message);

            // Restaurar botón
            payButton.disabled = false;
            payButton.textContent = `${sumatotal} €`;
        }
    }
});

/**
     * Función para crear alquiler usando POST
     * @param {string} usuario - DNI del usuario
     * @param {string} fechaInicio - Fecha inicio (YYYY-MM-DD HH:mm:ss)
     * @param {string} fechaFinal - Fecha final (YYYY-MM-DD HH:mm:ss)
     * @param {number} precio - Precio del alquiler
     * @returns {Promise} Promesa con los datos del alquiler creado
     */
async function crearReserva(UsuarioV, PistaV, AlquilerV, FInicioV, FFinalV) {
    try {
        // Obtener token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!csrfToken) {
            throw new Error('Token CSRF no encontrado. Asegúrate de tener <meta name="csrf-token" content="{{ csrf_token() }}"> en el <head>');
        }

        const response = await fetch('api/Reserva', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                Usuario: UsuarioV,
                Pista: PistaV,
                Alquiler: AlquilerV,
                FInicio: FInicioV,
                FFinal: FFinalV
            })
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }

        const data = await response.json();
        console.log('✅ Alquiler creado exitosamente:', data);

        return data;

    } catch (error) {
        console.error('❌ Error creando alquiler:', error);
        throw error;
    }
}