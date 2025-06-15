<!DOCTYPE html>
<html>
    <head>
        <title> Calendario Reservas</title>
        <script defer src="{{ asset('js/calendario.js') }}"></script>
    </head>
    <body>
        @include('banner')
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
            max-width: 1200px;
            margin: 0 auto;
        }

        .pageTitle {
            text-align: center;
            font-size: 2.2em;
            margin-bottom: 30px;
            color: #333;
        }

        /* Sección de selección de fecha */
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

        .dateInput {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
            width: 200px;
            transition: border-color 0.3s ease;
        }

        .dateInput:focus {
            outline: none;
            border-color: #2a9d8f;
        }

        /* Sección del calendario */
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

        /* Estilos del calendario */
        .calendario {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .calendario th {
            background: #2a9d8f;
            color: white;
            padding: 15px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 0.9em;
        }

        .calendario th:first-child {
            background: #238c7a;
        }

        .calendario td {
            padding: 12px 8px;
            text-align: center;
            border: 1px solid #eee;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }

        .calendario td:first-child {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
            cursor: default;
        }

        .calendario td.Libre {
            background: #d4edda;
            color: #155724;
            cursor: pointer;
            font-weight: 500;
        }

        .calendario td.Libre:hover {
            background: #c3e6cb;
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(42, 157, 143, 0.3);
        }

        .calendario td.Reservado {
            background: #f8d7da;
            color: #721c24;
            cursor: not-allowed;
            font-weight: 500;
        }

        .calendario td.Seleccionado {
            background: #2a9d8f;
            color: white;
            font-weight: bold;
            box-shadow: 0 0 0 3px rgba(42, 157, 143, 0.3);
        }

        .calendario td.Mantenimiento {
            background: #ffeaa7;
            color: #d68910;
            cursor: not-allowed;
            font-weight: 500;
        }

        /* Información de la selección */
        .selectionInfo {
            background: #e8f5f3;
            border: 1px solid #2a9d8f;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            display: none;
        }

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

        .selectionPrice {
            font-size: 1.2em;
            font-weight: bold;
            color: #2a9d8f;
        }

        /* Botón siguiente */
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
            margin: 0 auto;
        }

        .nextButton:hover {
            background: #238c7a;
        }

        .nextButton:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Leyenda */
        .legend {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .legendItem {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9em;
        }

        .legendColor {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

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

        /* Responsive */
        @media (max-width: 768px) {
            body {
                margin: 10px;
            }
            
            .pageTitle {
                font-size: 1.8em;
            }
            
            .calendario {
                font-size: 0.8em;
            }
            
            .calendario th,
            .calendario td {
                padding: 8px 4px;
            }
            
            .legend {
                gap: 15px;
            }
            
            .legendItem {
                font-size: 0.8em;
            }
        }

        @media (max-width: 480px) {
            .calendario th,
            .calendario td {
                padding: 6px 2px;
                font-size: 0.7em;
            }
        }
    </style>


    <div class="mainContainer">
        <h1 class="pageTitle">Reservar Pista</h1>

        <!-- Selección de fecha -->
        <div class="dateSection">
            <label class="dateLabel" for="fDeseada">Selecciona la fecha en la que quieres reservar:</label>
            <input type="date" id="fDeseada" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
        </div>

        <!-- Calendario de reservas -->
        <div class="calendarSection">
            <label class="calendarLabel">Selecciona la hora y la pista que quieres:</label>
            
            <!-- Leyenda -->
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
        <table class="calendario">
            
        </table>
        <button id="siguiente">Siguiente</button>
    </body>
</html>
