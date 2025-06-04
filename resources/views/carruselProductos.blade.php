<!DOCTYPE html>
<html>
    <header>
        <title> Calendario Reservas</title>
    </header>
    <body>
        @foreach ($productos as $producto)
        <div>
            <p>{{$producto->IDProducto}}<p>
            <p>{{$producto->Tipo}}</p>
        </div>
        @endforeach
        <p>Hola</p>
    </body>
</html>