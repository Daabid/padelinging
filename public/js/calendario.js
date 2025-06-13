const fDeseada = document.querySelector("#fDeseada");
const calendario = document.querySelector(".calendario");
const verifiReserva = document.querySelector(".verifiReserva");

let horas = [
  "Pistas",
  "08:00:00", "09:00:00", "10:00:00", "11:00:00", "12:00:00",
  "13:00:00", "14:00:00", "15:00:00", "16:00:00", "17:00:00",
  "18:00:00", "19:00:00", "20:00:00"
];

let selecciones = [];

document.addEventListener('DOMContentLoaded', () => {
  llamarApi(fDeseada.value);
});

// Escuchar el cambio de fecha
fDeseada.addEventListener('change', () => {
  llamarApi(fDeseada.value);
});

// Llamar API y construir tabla
function llamarApi(fecha) {
  fetch("/reservaDia/" + fecha)
    .then(reservas => reservas.json())
    .then(datos => {
      calendario.innerHTML = "";

      // Cabecera con horas
      let tr = document.createElement("tr");
      horas.forEach(hora => {
        let th = document.createElement("th");
        th.innerHTML = hora;
        tr.appendChild(th);
      });
      calendario.appendChild(tr);

      // Filas por pista
      Object.keys(datos).forEach(key => {
        const pista = datos[key];
        let tr = document.createElement("tr");
        let tdPista = document.createElement("td");
        tdPista.innerHTML = key;
        tr.appendChild(tdPista);

        pista.forEach((horaObj, index) => {
          let td = document.createElement("td");
          td.className = horaObj.estado;
          td.innerHTML = horaObj.estado;

          td.dataset.pista = key; // nombre de la pista
          td.dataset.hora = horas[index + 1]; // salto +1 porque el 0 es "Pistas"
          td.dataset.fecha = fDeseada.value;
          td.dataset.precio = horaObj['Precio'];

          // Solo clickeables si están libres
          if (horaObj.estado === "Libre") {
            td.style.cursor = "pointer";

            td.addEventListener('click', () => {
              let pista = td.dataset.pista;
              let hora = td.dataset.hora;
              let fecha = td.dataset.fecha;
              let precio = td.dataset.precio;

              let index = selecciones.findIndex(sel =>
                sel.pista === pista && sel.hora === hora && sel.fecha === fecha
              );

              if(index !== -1){
                selecciones.splice(index, 1);
                td.style.backgroundColor = "#d4edda";
                 td.innerHTML = "Libre";

              } 
              else{
                td.style.backgroundColor = "#2a9d8f";
                td.innerHTML = "Seleccionado";

                // Añadir a selecciones
                selecciones.push({ pista, hora, fecha, precio});
              }
              console.log("Selecciones:", selecciones);
            });
          }
          tr.appendChild(td);
        });
        calendario.appendChild(tr);
      });
    });
}

// Agrupar selecciones en reservas consecutivas
function agruparReservas(selecciones) {
  let agrupadas = [];

  // Agrupar por pista y fecha - usar un separador que no esté en la fecha
  let porPista = {};
  selecciones.forEach(sel => {
    let key = `${sel.pista}|${sel.fecha}|${sel.precio}`; // Usar "|" en vez de "-"
    if (!porPista[key]) porPista[key] = [];
    porPista[key].push(sel.hora);
  });

  // Ordenar y agrupar consecutivas
  Object.keys(porPista).forEach(key => {
    let [pista, fecha, precio] = key.split("|"); // Dividir por "|"
    let horasSel = porPista[key].sort();
    let inicio = horasSel[0];
    let anterior = inicio;

    for (let i = 1; i <= horasSel.length; i++) {
      let actual = horasSel[i];

      let h1 = parseInt(anterior.split(":")[0]);
      let h2 = actual ? parseInt(actual.split(":")[0]) : null;

      if (h2 !== null && h2 === h1 + 1) {
        anterior = actual;
        continue;
      }

      anterior = sumarHora(anterior, 1);
      let anteriorSpliteado = anterior.split(':')[0];
      let inicioSpliteado = inicio.split(':')[0];
      
      agrupadas.push({
        pista,
        fecha, // Ahora tendrá el valor completo "2025-06-11"
        hora_inicio: inicio,
        hora_fin: anterior,
        precio: parseFloat(precio) * (parseInt(anteriorSpliteado) - parseInt(inicioSpliteado))
      });

      inicio = actual;
      anterior = actual;
    }
  });

  return agrupadas;
}

// Sumar horas en formato hh:mm:ss
function sumarHora(hora, cantidad) {
  let [h, m, s] = hora.split(":").map(Number);
  h += cantidad;
  return `${String(h).padStart(2, "0")}:${String(m).padStart(2, "0")}:${String(s).padStart(2, "0")}`;
}

document.querySelector("#siguiente").addEventListener("click", () => {
  const reservasAgrupadas = agruparReservas(selecciones);

  // Opcional: guardar en localStorage para pasar a la siguiente página
  localStorage.setItem("reservas", JSON.stringify(reservasAgrupadas));

  // Redirigir a la página de pago
  window.location.href = "/reserva/pago";
});