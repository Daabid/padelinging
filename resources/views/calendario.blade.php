<!DOCTYPE html>
<html>
    <head>
        <title> Calendario Reservas</title>
        <!-- Carga el archivo JavaScript del calendario de forma diferida -->
        <script defer src="{{ asset('js/calendario.js') }}"></script>
    </head>
    <body>
        {{-- Incluye el banner --}}
        @include('banner')
        
        <style>
        /* ========================================
           RESET Y ESTILOS BASE
           ======================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f9f9f9;       /* Fondo gris claro */
            line-height: 1.6;
            color: #333;
        }

        /* ========================================
           HEADER Y NAVEGACIÓN
           ======================================== */
        header {
            background: white;
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);  /* Sombra sutil */
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Logo con emoji de tenis */
        .logo {
            font-size: 1.8em;
            font-weight: bold;
            color: #2a9d8f;         /* Verde azulado corporativo */
            display: flex;
            align-items: center;
        }

        /* Añade emoji de pelota de tenis antes del logo */
        .logo::before {
            content: "🎾";
            margin-right: 10px;
            font-size: 1.5em;
        }

        /* Botón de volver */
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

        /* ========================================
           CONTENEDOR PRINCIPAL
           ======================================== */
        .mainContainer {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Título principal de la página */
        .pageTitle {
            text-align: center;
            font-size: 2.2em;
            margin-bottom: 30px;
            color: #333;
        }

        /* ========================================
           SECCIÓN DE SELECCIÓN DE FECHA
           ======================================== */
        .dateSection {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .dateLabel {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            display: block;
        }

        /* Input de fecha */
        .dateInput {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
            width: 200px;
            transition: border-color 0.3s ease;
        }

        /* Focus en el input de fecha */
        .dateInput:focus {
            outline: none;
            border-color: #2a9d8f;  /* Borde verde al hacer focus */
        }

        /* ========================================
           SECCIÓN DEL CALENDARIO
           ======================================== */
        .calendarSection {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .calendarLabel {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            display: block;
        }

        /* ========================================
           TABLA DEL CALENDARIO
           ======================================== */
        .calendario {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;       /* Para que el border-radius funcione */
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        /* Encabezados de la tabla (días de la semana) */
        .calendario th {
            background: #2a9d8f;   /* Verde corporativo */
            color: white;
            padding: 15px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 0.9em;
        }

        /* Primera columna (horarios) con color más oscuro */
        .calendario th:first-child {
            background: #238c7a;   /* Verde más oscuro */
        }

        /* Celdas del calendario */
        .calendario td {
            padding: 12px 8px;
            text-align: center;
            border: 1px solid #eee;
            transition: all 0.3s ease;  /* Transición suave para todos los cambios */
            font-size: 0.9em;
        }

        /* Primera columna (horarios) - no interactiva */
        .calendario td:first-child {
            background: #f8f9fa;   /* Gris muy claro */
            font-weight: bold;
            color: #333;
            cursor: default;        /* Cursor normal, no clickeable */
        }

        /* ========================================
           ESTADOS DE LAS CELDAS DEL CALENDARIO
           ======================================== */
        
        /* Pista libre - disponible para reservar */
        .calendario td.Libre {
            background: #d4edda;   /* Verde claro */
            color: #155724;        /* Verde oscuro */
            cursor: pointer;
            font-weight: 500;
        }

        /* Efecto hover en pistas libres */
        .calendario td.Libre:hover {
            background: #c3e6cb;   /* Verde más intenso */
            transform: scale(1.05); /* Ligero aumento de tamaño */
            box-shadow: 0 2px 8px rgba(42, 157, 143, 0.3);
        }

        .calendario td.Reservado {
            background: #f8d7da;
            color: #721c24;
            cursor: not-allowed;
            font-weight: 500;
        }

        /* Pista seleccionada por el usuario */
        .calendario td.Seleccionado {
            background: #2a9d8f;   /* Verde corporativo */
            color: white;
            font-weight: bold;
            box-shadow: 0 0 0 3px rgba(42, 157, 143, 0.3); /* Borde destacado */
        }

        /* Pista en mantenimiento */
        .calendario td.Mantenimiento {
            background: #ffeaa7;   /* Amarillo claro */
            color: #d68910;        /* Amarillo oscuro */
            cursor: not-allowed;
            font-weight: 500;
        }

        /* ========================================
           INFORMACIÓN DE LA SELECCIÓN
           ======================================== */
        .selectionInfo {
            background: #e8f5f3;   /* Verde muy claro */
            border: 1px solid #2a9d8f;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            display: none;          /* Oculto por defecto */
        }

        /* Muestra la información cuando tiene la clase 'show' */
        .selectionInfo.show {
            display: block;
        }

        .selectionInfo h3 {
            color: #2a9d8f;
            margin-bottom: 10px;
        }

        .selectionDetails {
            color: #333;
            margin-bottom: 10px;
        }

        /* Precio destacado */
        .selectionPrice {
            font-size: 1.2em;
            font-weight: bold;
            color: #2a9d8f;
        }

        /* ========================================
           BOTÓN SIGUIENTE
           ======================================== */
        .nextButton {
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
            max-width: 200px;
            display: block;
            margin: 0 auto;         /* Centrado horizontalmente */
        }

        .nextButton:hover {
            background: #238c7a;   /* Verde más oscuro en hover */
        }

        /* Estado deshabilitado del botón */
        .nextButton:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* ========================================
           LEYENDA DE COLORES
           ======================================== */
        .legend {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;        /* Se ajusta en múltiples líneas si es necesario */
        }

        .legendItem {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9em;
        }

        /* Cuadrados de colores de la leyenda */
        .legendColor {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        /* Colores específicos de cada estado */
        .legendColor.libre {
            background: #d4edda;
        }

        .legendColor.ocupado {
            background: #f8d7da;
        }

        .legendColor.seleccionado {
            background: #2a9d8f;
        }

        .legendColor.mantenimiento {
            background: #ffeaa7;
        }

        /* ========================================
           RESPONSIVE DESIGN - TABLET
           ======================================== */
        @media (max-width: 768px) {
            body {
                margin: 10px;       /* Menos margen en dispositivos pequeños */
            }
            
            .pageTitle {
                font-size: 1.8em;   /* Título más pequeño */
            }
            
            .calendario {
                font-size: 0.8em;   /* Texto más pequeño en la tabla */
            }
            
            .calendario th,
            .calendario td {
                padding: 8px 4px;   /* Menos padding en celdas */
            }
            
            .legend {
                gap: 15px;          /* Menos espacio entre elementos de leyenda */
            }
            
            .legendItem {
                font-size: 0.8em;
            }
        }

        /* ========================================
           RESPONSIVE DESIGN - MÓVIL
           ======================================== */
        @media (max-width: 480px) {
            .calendario th,
            .calendario td {
                padding: 6px 2px;   /* Padding muy reducido */
                font-size: 0.7em;   /* Texto aún más pequeño */
            }
        }
    </style>

    <!-- ========================================
         CONTENIDO PRINCIPAL DE LA PÁGINA
         ======================================== -->
    <div class="mainContainer">
        <h1 class="pageTitle">Reservar Pista</h1>

        <!-- SECCIÓN DE SELECCIÓN DE FECHA -->
        <div class="dateSection">
            <label class="dateLabel" for="fDeseada">Selecciona la fecha en la que quieres reservar:</label>
            <!-- Input de fecha con valor mínimo = hoy y valor por defecto = hoy -->
            <input type="date" id="fDeseada" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
        </div>

        <!-- SECCIÓN DEL CALENDARIO DE RESERVAS -->
        <div class="calendarSection">
            <label class="calendarLabel">Selecciona la hora y la pista que quieres:</label>
            
            <!-- LEYENDA DE ESTADOS -->
            <!-- Explica los diferentes colores y estados de las pistas -->
            <div class="legend">
                <div class="legendItem">
                    <div class="legendColor libre"></div>
                    <span>Libre</span>
                </div>
                <div class="legendItem">
                    <div class="legendColor ocupado"></div>
                    <span>Ocupado</span>
                </div>
                <div class="legendItem">
                    <div class="legendColor seleccionado"></div>
                    <span>Seleccionado</span>
                </div>
                <div class="legendItem">
                    <div class="legendColor mantenimiento"></div>
                    <span>Mantenimiento</span>
                </div>
            </div>
            
        <!-- TABLA DEL CALENDARIO -->
        <!-- La tabla se genera dinámicamente con JavaScript -->
        <!-- Contendrá horarios en filas y pistas en columnas -->
        <table class="calendario">
            <!-- El contenido se genera dinámicamente con calendario.js -->
        </table>
        
        <!-- BOTÓN PARA PROCEDER AL SIGUIENTE PASO -->
        <button id="siguiente">Siguiente</button>
        
        </div>
    </div>
    </body>
</html>