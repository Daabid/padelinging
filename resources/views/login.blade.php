<!DOCTYPE html>
<html>
<head>
    <title>Login / Registro</title>
    <!-- Hoja de estilos externa para el diseño de login -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<style>
    /* === ESTILOS PARA CAJAS DE ERROR === */
    /* Caja de error con colores de Bootstrap para mensajes de validación */
    .error-box {
        background-color: #f8d7da;  /* Fondo rosa claro */
        color: #842029;             /* Texto rojo oscuro */
        padding: 10px;              /* Espaciado interno */
        border: 1px solid #f5c2c7;  /* Borde rosa */
        margin-bottom: 15px;        /* Margen inferior */
        border-radius: 4px;         /* Esquinas redondeadas */
    }
</style>

<body>
    <!-- === FORMULARIO DE LOGIN === -->
    <!-- Formulario principal para iniciar sesión -->
    <div id="login-form" class="form-container">
        
        <!-- === MANEJO DE ERRORES LARAVEL === -->
        <!-- Blade directive que muestra errores de validación del servidor -->
        @if ($errors->any())
        <div class="error-box">
            {{ $errors->first() }}
        </div>
        @endif
        
        <h2>Iniciar sesión</h2>
        
        <!-- === FORMULARIO DE AUTENTICACIÓN === -->
        <!-- Formulario que envía datos al endpoint de login -->
        <form method="POST" action="{{ url('/login') }}">
            <!-- Token CSRF para protección contra ataques CSRF -->
            @csrf
            
            <!-- Campo de correo electrónico -->
            <label for="correo-login">Correo:</label>
            <input type="email" id="correo-login" name="correo" required>

            <!-- Campo de contraseña -->
            <label for="password-login">Contraseña:</label>
            <input type="password" id="password-login" name="password" required>

            <!-- Botón de envío -->
            <button type="submit">Entrar</button>
        </form>
        
        <!-- === ENLACE PARA CAMBIAR A REGISTRO === -->
        <!-- Enlace JavaScript para alternar entre formularios -->
        <p class="switch-form">
            ¿No tienes cuenta? <a href="#" id="show-register">Registrar</a>
        </p>
    </div>

    <!-- === FORMULARIO DE REGISTRO === -->
    <!-- Formulario de registro inicialmente oculto -->
    <div id="register-form" class="form-container" style="display:none;">
        
        <!-- === CONTENEDOR DE ERRORES JAVASCRIPT === -->
        <!-- Caja de error manejada por JavaScript para validaciones del lado cliente -->
        <div id="register-error" class="error-box" style="display:none;"></div>

        <h2>Registro</h2>
        
        <!-- === FORMULARIO DE REGISTRO === -->
        <!-- Formulario completo para crear nueva cuenta -->
        <form id="registerForm" method="POST" action="{{ url('/register') }}">
            <!-- Token CSRF para protección -->
            @csrf

            <!-- === CAMPOS PERSONALES === -->
            <!-- Campo nombre -->
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <!-- Campo apellido -->
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <!-- Campo DNI (con validación JavaScript) -->
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required>

            <!-- Campo correo electrónico -->
            <label for="correo-register">Correo:</label>
            <input type="email" id="correo-register" name="correo" required>

            <!-- Campo fecha de nacimiento -->
            <label for="fecha-nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha-nacimiento" name="fecha_nacimiento" required>

            <!-- Campo contraseña -->
            <label for="password-register">Contraseña:</label>
            <input type="password" id="password-register" name="password" required>

            <!-- === CAMPO OCULTO === -->
            <!-- Campo oculto que asigna rol por defecto -->
            <input type="hidden" name="rol" value="comprador">

            <!-- Botón de envío -->
            <button type="submit">Registrarse</button>
        </form>
        
        <!-- === ENLACE PARA CAMBIAR A LOGIN === -->
        <!-- Enlace JavaScript para volver al formulario de login -->
        <p class="switch-form">
            ¿Ya tienes cuenta? <a href="#" id="show-login">Iniciar sesión</a>
        </p>
    </div>

<script>
    /**
     * === VARIABLES GLOBALES ===
     * @description Referencias a elementos DOM para manipulación
     */
    
    /** @type {HTMLElement} Contenedor del formulario de login */
    const loginForm = document.getElementById('login-form');
    
    /** @type {HTMLElement} Contenedor del formulario de registro */
    const registerForm = document.getElementById('register-form');
    
    /** @type {HTMLElement} Enlace para mostrar formulario de registro */
    const showRegister = document.getElementById('show-register');
    
    /** @type {HTMLElement} Enlace para mostrar formulario de login */
    const showLogin = document.getElementById('show-login');
    
    /** @type {HTMLInputElement} Campo de entrada del DNI */
    const dniInput = document.getElementById('dni');
    
    /** @type {HTMLFormElement} Formulario de registro */
    const registerFormElement = document.getElementById('registerForm');
    
    /** @type {HTMLElement} Caja para mostrar errores de registro */
    const registerErrorBox = document.getElementById('register-error');

    /**
     * === EVENT LISTENER: MOSTRAR REGISTRO ===
     * @description Maneja el clic en el enlace "Registrar"
     * Oculta el formulario de login y muestra el de registro
     */
    showRegister.addEventListener('click', function(e) {
        e.preventDefault(); // Evita el comportamiento por defecto del enlace
        
        // Oculta el formulario de login
        loginForm.style.display = 'none';
        
        // Muestra el formulario de registro
        registerForm.style.display = 'block';
        
        // Limpia cualquier error previo al cambiar de formulario
        registerErrorBox.style.display = 'none';
    });

    /**
     * === EVENT LISTENER: MOSTRAR LOGIN ===
     * @description Maneja el clic en el enlace "Iniciar sesión"
     * Oculta el formulario de registro y muestra el de login
     */
    showLogin.addEventListener('click', function(e) {
        e.preventDefault(); // Evita el comportamiento por defecto del enlace
        
        // Oculta el formulario de registro
        registerForm.style.display = 'none';
        
        // Muestra el formulario de login
        loginForm.style.display = 'block';
    });

    /**
     * Valida un DNI español según el algoritmo oficial
     * @description Verifica que el DNI tenga el formato correcto (8 dígitos + letra)
     * y que la letra corresponda correctamente al número según el algoritmo oficial
     * @param {string} dni - DNI a validar en formato "12345678A"
     * @returns {boolean} true si el DNI es válido, false en caso contrario
     */
    function validarDNI(dni) {
        // Tabla de letras para validación del DNI español
        const letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
        
        // Expresión regular: 8 dígitos seguidos de una letra
        const dniRegex = /^(\d{8})([A-Z])$/i;

        // Extrae número y letra del DNI
        const match = dni.match(dniRegex);
        if (!match) return false; // Formato incorrecto

        const numero = parseInt(match[1]);     // Los 8 dígitos
        const letra = match[2].toUpperCase();  // La letra (convertida a mayúscula)

        // Calcula la letra correcta usando el algoritmo oficial
        // La letra se obtiene dividiendo el número entre 23 y tomando el resto
        const letraCorrecta = letras[numero % 23];
        
        // Compara la letra proporcionada con la calculada
        return letra === letraCorrecta;
    }

    /**
     * === EVENT LISTENER: VALIDACIÓN DE FORMULARIO ===
     * @description Intercepta el envío del formulario de registro
     * para validar el DNI antes de enviarlo al servidor
     */
    registerFormElement.addEventListener('submit', function(e) {
        // Obtiene el valor del DNI sin espacios
        const dniValor = dniInput.value.trim();

        // Valida el DNI usando la función personalizada
        if (!validarDNI(dniValor)) {
            // Si el DNI no es válido:
            e.preventDefault(); // Evita el envío del formulario
            
            // Muestra mensaje de error
            registerErrorBox.innerText = 'El DNI no es válido. Asegúrate de introducir 8 números seguidos de la letra correcta.';
            registerErrorBox.style.display = 'block';
        } else {
            // Si el DNI es válido, oculta cualquier error previo
            registerErrorBox.style.display = 'none';
            // El formulario se envía normalmente
        }
    });
</script>

</body>
</html>