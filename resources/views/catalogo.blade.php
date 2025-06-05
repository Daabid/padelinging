<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Catálogo de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f9f9f9;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .producto-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: box-shadow 0.3s ease;
        }
        .producto-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .producto-imagen {
            width: 100%;
            height: 150px;
            object-fit: contain;
            margin-bottom: 15px;
            background: #fafafa;
            border-radius: 6px;
        }
        .producto-nombre {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 10px;
            color: #333;
            text-align: center;
        }
        .producto-descripcion {
            flex-grow: 1;
            font-size: 0.9em;
            color: #666;
            margin-bottom: 15px;
        }
        .producto-precio {
            font-weight: bold;
            color: #2a9d8f;
            font-size: 1.05em;
        }

        button {
            background: white;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .cartIcon {
            height: 25px;
            width: 25px;
        }

        .producto-precio-carrito {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px auto 0;
            gap: 125px;
        }


        .btn-carrito {
            background: none;
            border: none;
            padding: 0;
        }

        .cartIcon {
            height: 20px;
            width: 20px;
            transition: transform 0.2s ease;
        }

        .cartIcon:hover {
            transform: scale(1.2);
        }

    </style>
</head>
<body>

    <h1>Catálogo de Productos en Venta</h1>

    @if($productos->isEmpty())
        <p style="text-align:center;">No hay productos disponibles en venta.</p>
    @else
        <div class="grid-container">
            @foreach($productos as $producto)
                <div class="producto-card">
                    <a href="{{ route('producto.show', $producto->IDProducto) }}">
                        @if($producto->URL)
                        <img src="{{ asset('/images/material/' . $producto->URL . 'Frente.jpg') }}" alt="{{ $producto->nombre }}" class="producto-imagen" />
                        @else
                        <div class="producto-imagen" style="display:flex; align-items:center; justify-content:center; color:#bbb;">Sin imagen</div>
                        @endif
                    </a>
                    <div class="producto-nombre">{{ $producto->Nombre }}</div>
                    <div class="producto-precio-carrito">
                    <div class="producto-precio">{{ number_format($producto->Precio, 2) }}€</div>
                        <form class="form-carrito">
                            @csrf
                            <button type="button" class="btn-carrito">
                                <img class="cartIcon" src="{{ asset('images/iconos/carrito.png') }}" style="cursor: pointer;" />
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        document.querySelectorAll('.btn-carrito').forEach(btn => {
            btn.addEventListener('click', function (event) {
                event.preventDefault();

                const icono = this.querySelector('.cartIcon');

                // Cambia a GIF
                icono.src = "{{ asset('images/iconos/carrito.gif') }}";

                // Vuelve al PNG después de 2 segundos
                setTimeout(() => {
                    icono.src = "{{ asset('images/iconos/carrito.png') }}";
                }, 1000);
            });
        });
    </script>

</body>
</html>
