<!DOCTYPE html>
<html>
<head>
    <title>Login / Registro</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<style>
    .error-box {
        background-color: #f8d7da;
        color: #842029;
        padding: 10px;
        border: 1px solid #f5c2c7;
        margin-bottom: 15px;
        border-radius: 4px;
    }

</style>
<body>

    <div id="login-form" class="form-container">
        @if ($errors->any())
        <div class="error-box">
            {{ $errors->first() }}
        </div>
        @endif
        <h2>Iniciar sesión</h2>
        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <label for="correo-login">Correo:</label>
            <input type="email" id="correo-login" name="correo" required>

            <label for="password-login">Contraseña:</label>
            <input type="password" id="password-login" name="password" required>

            <button type="submit">Entrar</button>
        </form>
        <p class="switch-form">
            ¿No tienes cuenta? <a href="#" id="show-register">Registrar</a>
        </p>
    </div>

    <div id="register-form" class="form-container" style="display:none;">
        {{-- Contenedor para errores de JavaScript --}}
        <div id="register-error" class="error-box" style="display:none;"></div>

        <h2>Registro</h2>
        <form id="registerForm" method="POST" action="{{ url('/register') }}">
            @csrf

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required>

            <label for="correo-register">Correo:</label>
            <input type="email" id="correo-register" name="correo" required>

            <label for="fecha-nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha-nacimiento" name="fecha_nacimiento" required>

            <label for="password-register">Contraseña:</label>
            <input type="password" id="password-register" name="password" required>

            {{-- Campo oculto con rol por defecto --}}
            <input type="hidden" name="rol" value="comprador">

            <button type="submit">Registrarse</button>
        </form>
        <p class="switch-form">
            ¿Ya tienes cuenta? <a href="#" id="show-login">Iniciar sesión</a>
        </p>
    </div>

<script>
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const showRegister = document.getElementById('show-register');
    const showLogin = document.getElementById('show-login');
    const dniInput = document.getElementById('dni');
    const registerFormElement = document.getElementById('registerForm');
    const registerErrorBox = document.getElementById('register-error');

    showRegister.addEventListener('click', function(e) {
        e.preventDefault();
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        registerErrorBox.style.display = 'none'; // Limpiar errores al cambiar
    });

    showLogin.addEventListener('click', function(e) {
        e.preventDefault();
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
    });

    function validarDNI(dni) {
        const letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
        const dniRegex = /^(\d{8})([A-Z])$/i;

        const match = dni.match(dniRegex);
        if (!match) return false;

        const numero = parseInt(match[1]);
        const letra = match[2].toUpperCase();

        const letraCorrecta = letras[numero % 23];
        return letra === letraCorrecta;
    }

    registerFormElement.addEventListener('submit', function(e) {
        const dniValor = dniInput.value.trim();

        if (!validarDNI(dniValor)) {
            e.preventDefault();
            registerErrorBox.innerText = 'El DNI no es válido. Asegúrate de introducir 8 números seguidos de la letra correcta.';
            registerErrorBox.style.display = 'block';
        } else {
            registerErrorBox.style.display = 'none';
        }
    });
</script>

</body>
</html>
