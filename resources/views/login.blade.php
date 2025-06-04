<!DOCTYPE html>
<html>
<head>
    <title>Login / Registro</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
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
        <h2>Registro</h2>
        <form method="POST" action="{{ url('/register') }}">
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

    showRegister.addEventListener('click', function(e) {
        e.preventDefault();
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
    });

    showLogin.addEventListener('click', function(e) {
        e.preventDefault();
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
    });
</script>

</body>
</html>
