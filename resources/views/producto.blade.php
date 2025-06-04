<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $producto->Nombre }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f4f8;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .producto-detalle {
            display: flex;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 900px;
            width: 90%;
            padding: 30px;
            gap: 30px;
            align-items: flex-start;
        }

        .carrusel-container {
            position: relative;
            flex: 1 1 300px;
            max-width: 300px;
        }

        .producto-imagen {
            width: 100%;
            border-radius: 12px;
            background-color: #fafafa;
            object-fit: contain;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            user-select: none;
        }

        .flecha {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            color: black;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            line-height: 35px;
            text-align: center;
            user-select: none;
            transition: background 0.3s ease;
        }

        .flecha-izquierda {
            left: 5px;
        }

        .flecha-derecha {
            right: 5px;
        }

        .producto-info {
            flex: 2 1 400px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .producto-info h1 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 2em;
        }

        .precio-y-boton {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 18px;
        }

        .producto-precio {
            color: #2a9d8f;
            font-weight: 700;
            font-size: 1.5em;
            margin: 0;
        }

        .btn-carrito {
            background-color: #2a9d8f;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 1em;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            white-space: nowrap;
        }

        .btn-carrito:hover {
            background-color: #21867a;
        }

        .producto-descripcion {
            color: #555;
            font-size: 1em;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        .producto-descripcion p {
            margin: 0 0 10px 0;
        }

        .producto-descripcion ul.caracteristicas {
            list-style-type: disc;
            margin: 0 0 15px 20px;
            padding: 0;
            color: #555;
        }

        .producto-descripcion p.titulo-seccion {
            font-weight: 700;
            margin-top: 15px;
            margin-bottom: 8px;
            color: #333;
        }

        .volver-link {
            margin-top: 30px;
            display: inline-block;
            text-decoration: none;
            color: #2a9d8f;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .volver-link:hover {
            color: #21867a;
        }

        /* Responsive */
        @media (max-width: 700px) {
            body {
                padding: 10px;
            }
            .producto-detalle {
                flex-direction: column;
                padding: 20px;
                max-width: 100%;
            }
            .producto-imagen {
                max-width: 100%;
                height: auto;
                margin-bottom: 20px;
            }
            .producto-info {
                flex: unset;
            }
            .precio-y-boton {
                flex-direction: column;
                align-items: flex-start;
            }
            .btn-carrito {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <div class="producto-detalle">
        <div class="carrusel-container">
            <button class="flecha flecha-izquierda" id="prevBtn">&#8249;</button>
            <img id="imagenProducto" src="{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}" alt="{{ $producto->Nombre }}" class="producto-imagen" />
            <button class="flecha flecha-derecha" id="nextBtn">&#8250;</button>
        </div>

        <div class="producto-info">
            <h1>{{ $producto->Nombre }}</h1>

            <div class="precio-y-boton">
                <div class="producto-precio">{{ number_format($producto->Precio, 2) }}€</div>

                <form method="POST" action="">
                    @csrf
                    <button type="submit" class="btn-carrito">Añadir al carrito</button>
                </form>
            </div>

            @php
                $desc = e($producto->Descripción);
                $lineas = explode("\n", $desc);

                $htmlDescripcion = '';
                $enLista = false;

                foreach ($lineas as $linea) {
                    $linea = trim($linea);
                    if ($linea === '') {
                        if ($enLista) {
                            $htmlDescripcion .= '</ul>';
                            $enLista = false;
                        }
                    } elseif (str_ends_with($linea, ':')) {
                        if ($enLista) {
                            $htmlDescripcion .= '</ul>';
                            $enLista = false;
                        }
                        $htmlDescripcion .= '<p class="titulo-seccion">' . $linea . '</p>';
                    } elseif (preg_match('/^(Peso|Forma|Balance|Núcleo|Superficie|Perfil|Grip|Taladrado):/', $linea)) {
                        if (!$enLista) {
                            $htmlDescripcion .= '<ul class="caracteristicas">';
                            $enLista = true;
                        }
                        $htmlDescripcion .= '<li>' . $linea . '</li>';
                    } else {
                        if ($enLista) {
                            $htmlDescripcion .= '</ul>';
                            $enLista = false;
                        }
                        $htmlDescripcion .= '<p>' . $linea . '</p>';
                    }
                }
                if ($enLista) {
                    $htmlDescripcion .= '</ul>';
                }
            @endphp

            <div class="producto-descripcion">{!! $htmlDescripcion !!}</div>

            <a href="{{ url()->previous() }}" class="volver-link">← Volver al catálogo</a>
        </div>
    </div>

    <script>
        const imagenProducto = document.getElementById('imagenProducto');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        const imagenes = [
            "{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}",
            "{{ asset('/images/material/' . $producto->URL . 'Lateral1.jpg') }}",
            "{{ asset('/images/material/' . $producto->URL . 'Lateral2.jpg') }}"
        ];

        let indiceActual = 0;

        function mostrarImagen(indice) {
            imagenProducto.src = imagenes[indice];
        }

        prevBtn.addEventListener('click', () => {
            indiceActual = (indiceActual - 1 + imagenes.length) % imagenes.length;
            mostrarImagen(indiceActual);
        });

        nextBtn.addEventListener('click', () => {
            indiceActual = (indiceActual + 1) % imagenes.length;
            mostrarImagen(indiceActual);
        });
    </script>

</body>
</html>
