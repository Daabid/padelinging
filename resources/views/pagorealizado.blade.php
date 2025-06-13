<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Confirmación</title>
    </head>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log(localStorage["reservas"]);
            
            if (!localStorage["reservas"]) {
                console.log("No hay reservas guardadas");
                return;
            }
            
            let reservas = JSON.parse(localStorage["reservas"]);
            console.log(reservas);
            
            function formatearFechaLocal(fecha, hora) {
                let fechaCompleta = new Date(`${fecha} ${hora}`);
                let año = fechaCompleta.getFullYear();
                let mes = String(fechaCompleta.getMonth() + 1).padStart(2, '0');
                let dia = String(fechaCompleta.getDate()).padStart(2, '0');
                let horas = String(fechaCompleta.getHours()).padStart(2, '0');
                let minutos = String(fechaCompleta.getMinutes()).padStart(2, '0');
                let segundos = String(fechaCompleta.getSeconds()).padStart(2, '0');
                
                return `${año}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
            }
            
            reservas.forEach(reserva => {
                console.log(reserva);
                let user = '41514002Z';
                let Finicio = formatearFechaLocal(reserva["fecha"], reserva["hora_inicio"]);
                let FFinal = formatearFechaLocal(reserva["fecha"], reserva["hora_fin"]);
                console.log(Finicio);

                fetch(`/Alquiler/${user}&&${Finicio}&&${FFinal}&&${reserva["precio"]}`)
                    .then(alquiler => alquiler.json())
                    .then(datos => {
                        console.log(datos);
                        let alquilerID = datos['alquiler']["ID"];

                        return new Promise(resolve => {
                        setTimeout(() => {
                            resolve(alquilerID);
                        }, 100); // 100ms de delay
                    });
                })
                .then(alquilerID => {
                    console.log("🌐 Creando reserva después del delay...");
                    return fetch(`/reserva/pago/${user}&&${reserva["pista"]}&&${alquilerID}&&${Finicio}&&${FFinal}`);
                })
                    .then(response => response.json())
                    .then(resultado => {
                        console.log("Pago procesado:", resultado);
                    })
                    .catch(error => {
                        console.error("Error en las peticiones:", error);
                    });
            });
        });
    </script>
    <body>

    </body>
</html>