<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago - padelinging</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f9f9f9;
            line-height: 1.6;
            color: #333;
        }

        /* Header */
        header {
            background: white;
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo {
            font-size: 1.8em;
            font-weight: bold;
            color: #2a9d8f;
            display: flex;
            align-items: center;
        }

        .logo::before {
            content: "🎾";
            margin-right: 10px;
            font-size: 1.5em;
        }

        .backButton {
            background: #f0f0f0;
            color: #333;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .backButton:hover {
            background: #e0e0e0;
        }

        /* Main Container */
        .mainContainer {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        .pageTitle {
            text-align: center;
            font-size: 2.2em;
            margin-bottom: 30px;
            color: #333;
        }

        /* Formulario de Pago */
        .paymentForm {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .sectionTitle {
            font-size: 1.4em;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #2a9d8f;
            padding-bottom: 10px;
        }

        .formGroup {
            margin-bottom: 20px;
        }

        .formRow {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .formGroup label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .formGroup input, .formGroup select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .formGroup input:focus, .formGroup select:focus {
            outline: none;
            border-color: #2a9d8f;
        }

        .cardNumber {
            position: relative;
        }

        .cardIcon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2em;
        }

        .securityInfo {
            font-size: 0.8em;
            color: #666;
            margin-top: 5px;
        }

        .checkbox {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .checkbox input {
            width: auto;
            margin-right: 10px;
        }

        .payButton {
            background: #2a9d8f;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 15px 30px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .payButton:hover {
            background: #238c7a;
        }

        .payButton:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Resumen de la Reserva */
        .reservationSummary {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .summaryItem {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .summaryItem:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.1em;
            color: #2a9d8f;
        }

        .pistaInfo {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .pistaName {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .pistaDetails {
            font-size: 0.9em;
            color: #666;
        }

        .securityBadges {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .securityBadge {
            font-size: 2em;
            opacity: 0.7;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .mainContainer {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .formRow {
                grid-template-columns: 1fr;
            }
            
            body {
                margin: 10px;
            }
            
            .pageTitle {
                font-size: 1.8em;
            }
        }

        /* Validación de formularios */
        .formGroup input.error {
            border-color: #e74c3c;
        }

        .errorMessage {
            color: #e74c3c;
            font-size: 0.8em;
            margin-top: 5px;
        }

        .successMessage {
            color: #27ae60;
            font-size: 0.8em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">PadelReserva</div>
            <a href="#" class="backButton" onclick="history.back()">← Volver</a>
        </nav>
    </header>

    <h1 class="pageTitle">Finalizar Reserva</h1>

    <div class="mainContainer">
        <!-- Formulario de Pago -->
        <div class="paymentForm">
            <form id="paymentForm">
                <!-- Información de Pago -->
                <h2 class="sectionTitle">Información de Pago</h2>

                <div class="formGroup">
                    <label for="numeroTarjeta">Número de Tarjeta *</label>
                    <div class="cardNumber">
                        <input type="text" id="numeroTarjeta" name="numeroTarjeta" placeholder="1234 5678 9012 3456" required maxlength="19">
                        <span class="cardIcon" id="cardIcon">💳</span>
                    </div>
                </div>

                <div class="formRow">
                    <div class="formGroup">
                        <label for="fechaVencimiento">Fecha de Vencimiento *</label>
                        <input type="text" id="fechaVencimiento" name="fechaVencimiento" placeholder="MM/AA" required maxlength="5">
                    </div>
                    <div class="formGroup">
                        <label for="cvv">CVV *</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123" required maxlength="4">
                        <div class="securityInfo">Los 3 dígitos del reverso de tu tarjeta</div>
                    </div>
                </div>

                <div class="formGroup">
                    <label for="nombreTarjeta">Nombre en la Tarjeta *</label>
                    <input type="text" id="nombreTarjeta" name="nombreTarjeta" required>
                </div>

                <!-- Dirección de Facturación -->
                <h2 class="sectionTitle">Dirección de Facturación</h2>

                <div class="formGroup">
                    <label for="direccion">Dirección *</label>
                    <input type="text" id="direccion" name="direccion" required>
                </div>

                <div class="formRow">
                    <div class="formGroup">
                        <label for="ciudad">Ciudad *</label>
                        <input type="text" id="ciudad" name="ciudad" required>
                    </div>
                    <div class="formGroup">
                        <label for="codigoPostal">Código Postal *</label>
                        <input type="text" id="codigoPostal" name="codigoPostal" required>
                    </div>
                </div>

                <div class="formGroup">
                    <label for="pais">País *</label>
                    <select id="pais" name="pais" required>
                        <option value="">Seleccionar país</option>
                        <option value="ES">España</option>
                        <option value="FR">Francia</option>
                        <option value="PT">Portugal</option>
                        <option value="IT">Italia</option>
                        <option value="DE">Alemania</option>
                    </select>
                </div>

                <!-- Términos y Condiciones -->
                <div class="checkbox">
                    <input type="checkbox" id="terminos" name="terminos" required>
                    <label for="terminos">Acepto los <a href="#" style="color: #2a9d8f;">términos y condiciones</a> y la <a href="#" style="color: #2a9d8f;">política de privacidad</a> *</label>
                </div>

                <button type="submit" class="payButton" id="payButton">
                    Calculando...
                </button>

                <!-- Badges de Seguridad -->
                <div class="securityBadges">
                    <span class="securityBadge" title="Visa">💳</span>
                    <span class="securityBadge" title="Mastercard">💳</span>
                    <span class="securityBadge" title="SSL Seguro">🔒</span>
                </div>
            </form>
        </div>

        <!-- Resumen de la Reserva -->
        <div class="reservationSummary">
            <script>
                let reservas = JSON.parse(localStorage["reservas"]);
                console.log(reservas);
                sumatotal =0;
                const reservationSummary = document.querySelector(".reservationSummary");
                reservas.forEach(reserva =>{
                    sumatotal += reserva["precio"];
                    reservationSummary.innerHTML = `
                    <div class="pistaInfo">
                        <div class="pistaName">${reserva["pista"]}</div>
                        <div class="pistaDetails">
                                
                            📅 Fecha: ${reserva["fecha"]}<br>
                            🕒 Hora: ${reserva["hora_inicio"]} - ${reserva["hora_fin"]}<br>
                            💲  Precio: ${reserva["precio"]} €
                        </div>
                    </div>`+ reservationSummary.innerHTML;
                    })
                setTimeout(() => {
                        reservationSummary.innerHTML = "<h2 class='sectionTitle'>Resumen de tu Reserva</h2>" + reservationSummary.innerHTML;
                        document.getElementById('totalPagar').innerHTML = sumatotal + ' €';
                        document.getElementById('payButton').innerHTML = sumatotal + ' €';
                    }, 2000);
            </script>
            
            <div class="summaryItem">
                <span>Total a pagar</span>
                <span id="totalPagar">Calculando...</span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('paymentForm');
            const numeroTarjeta = document.getElementById('numeroTarjeta');
            const fechaVencimiento = document.getElementById('fechaVencimiento');
            const cvv = document.getElementById('cvv');
            const cardIcon = document.getElementById('cardIcon');

            // Formatear número de tarjeta
            numeroTarjeta.addEventListener('input', function(e) {
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
            fechaVencimiento.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 2) {
                    value = value.slice(0,2) + '/' + value.slice(2,4);
                }
                e.target.value = value;
            });

            // Solo números en CVV
            cvv.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });

            // Validación del formulario
            form.addEventListener('submit', function(e) {
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
                if(numeroTarjeta.value.replace(/\s/g, '').length < 16){
                    showError(numeroTarjeta, 'El número de tarjeta debe tener 16 dígitos');
                    isValid = false;
                }

                if(fechaVencimiento.value.length < 5){
                    showError(fechaVencimiento, 'Formato: MM/AA');
                    isValid = false;
                }

                if(cvv.value.length < 3){
                    showError(cvv, 'El CVV debe tener 3 o 4 dígitos');
                    isValid = false;
                }

                // Validar email
                // if(!emailRegex.test(document.getElementById('email').value)){
                //     showError(document.getElementById('email'), 'Ingresa un email válido');
                //     isValid = false;
                // }

                if(isValid){
                    // Simular procesamiento de pago
                    const payButton = document.getElementById('payButton');
                    payButton.disabled = true;
                    payButton.textContent = 'Procesando...';
                    
                    setTimeout(() => {
                        alert('¡Pago realizado con éxito! Te hemos enviado la confirmación por email.');
                        window.location.href = "/reserva/pago/realizado";
                    }, 2000);
                }
            });

            function showError(field, message){
                field.classList.add('error');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'errorMessage';
                errorDiv.textContent = message;
                field.parentNode.appendChild(errorDiv);
            }
        });
    </script>
</body>
</html>