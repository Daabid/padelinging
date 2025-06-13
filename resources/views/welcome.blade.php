<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padelinging</title>
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

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #2a9d8f;
        }

        .cta-button {
            background: #2a9d8f;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .cta-button:hover {
            background: #238c7a;
            transform: translateY(-1px);
        }

        /* Hero Section */
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            color: #333;
        }

        .hero-subtitle {
            text-align: center;
            font-size: 1.2em;
            color: #666;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* CTA Section */
        .cta-section {
            background: #2a9d8f;
            border-radius: 8px;
            padding: 50px 30px;
            margin-bottom: 40px;
            text-align: center;
            color: white;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 40px;
        }

        .cta-content h2 {
            font-size: 2.2em;
            margin-bottom: 15px;
        }

        .cta-content p {
            font-size: 1.1em;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .cta-button-large {
            background: white;
            color: #2a9d8f;
            padding: 15px 35px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: inline-block;
        }

        .cta-button-large:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Pistas Grid */
        .section-title {
            text-align: center;
            font-size: 2em;
            margin-bottom: 30px;
            color: #333;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            margin-bottom: 50px;
        }

        .pista-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .pista-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }

        .pista-imagen {
            width: 100%;
            height: 180px;
            object-fit: cover;
            margin-bottom: 15px;
            background: linear-gradient(45deg, #2a9d8f, #4ecdc4);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3em;
            color: white;
        }

        .pista-nombre {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 10px;
            color: #333;
            text-align: center;
        }

        .pista-descripcion {
            flex-grow: 1;
            font-size: 0.9em;
            color: #666;
            margin-bottom: 15px;
            text-align: center;
        }

        .pista-precio {
            font-weight: bold;
            color: #2a9d8f;
            font-size: 1.1em;
            text-align: center;
            margin-bottom: 15px;
        }

        .pista-precio-reserva {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .btn-reservar {
            background: #2a9d8f;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
            flex: 1;
        }

        .btn-reservar:hover {
            background: #238c7a;
        }

        /* Features Section */
        .features-section {
            background: white;
            border-radius: 8px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 40px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .feature-item {
            text-align: center;
            padding: 20px;
        }

        .feature-icon {
            font-size: 3em;
            margin-bottom: 15px;
            display: block;
        }

        .feature-title {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .feature-description {
            color: #666;
            font-size: 0.9em;
        }

        /* Footer */
        footer {
            background: white;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .nav-links {
                display: none;
            }
            
            h1 {
                font-size: 2em;
            }
            
            body {
                margin: 10px;
            }
        }
    </style>
</head>
@include("banner")
<body>
    <!-- Hero Section -->
    <section class="hero">
        <h1>Reserva tu Pista de Pádel</h1>
        <p class="hero-subtitle">Encuentra y reserva las mejores pistas de pádel en tu ciudad. Fácil, rápido y al mejor precio.</p>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2>¿Listo para jugar?</h2>
            <p>Explora todas nuestras pistas disponibles y encuentra la perfecta para ti</p>
            <a href="#buscar" class="cta-button-large">Reservar pista</a>
        </div>
    </section>

    <!-- Pistas Disponibles -->
    <section>
        <h2 class="section-title">Pistas Destacadas</h2>
        <div class="grid-container">
            <div class="pista-card">
                <div class="pista-imagen">🎾</div>
                <div class="pista-nombre">Club Deportivo Madrid</div>
                <div class="pista-descripcion">Pista cubierta con césped artificial premium. Incluye vestuarios y parking gratuito.</div>
                <div class="pista-precio">25€/hora</div>
                <div class="pista-precio-reserva">
                    <button class="btn-reservar">Reservar Ahora</button>
                </div>
            </div>

            <div class="pista-card">
                
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <h2 class="section-title">¿Por qué elegir PadelReserva?</h2>
        <div class="features-grid">
            <div class="feature-item">
                <span class="feature-icon">⚡</span>
                <h3 class="feature-title">Reserva Instantánea</h3>
                <p class="feature-description">Reserva tu pista en segundos, disponible 24/7 desde tu móvil o ordenador.</p>
            </div>
            <div class="feature-item">
                <span class="feature-icon">💰</span>
                <h3 class="feature-title">Mejores Precios</h3>
                <p class="feature-description">Comparamos precios para ofrecerte las mejores ofertas y descuentos exclusivos.</p>
            </div>
            <div class="feature-item">
                <span class="feature-icon">🏆</span>
                <h3 class="feature-title">Calidad Garantizada</h3>
                <p class="feature-description">Solo trabajamos con clubes verificados y pistas en excelente estado.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 PadelReserva. Todos los derechos reservados.</p>
        <p>Contacto: info@padelreserva.com | Tel: 900 123 456</p>
    </footer>

    <script>
        // Funcionalidad básica
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar botones de reserva
            document.querySelectorAll('.btn-reservar').forEach(button => {
                button.addEventListener('click', function() {
                    const pistaName = this.closest('.pista-card').querySelector('.pista-nombre').textContent;
                    alert(`Reservando pista en: ${pistaName}. ¡Redirigiendo al formulario de reserva!`);
                });
            });

            // Manejar CTA button
            document.querySelector('.cta-button-large').addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = "/reserva";
            });

            // Smooth scroll para navegación
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
        });
    </script>
</body>
</html>