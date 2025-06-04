let fDeseada = document.querySelector("#fDeseada");
let calendario = document.querySelector(".calendario");
let horas = [ "Pistas",
  "08:00:00", "09:00:00", "10:00:00", "11:00:00", "12:00:00",
  "13:00:00", "14:00:00", "15:00:00", "16:00:00", "17:00:00",
  "18:00:00", "19:00:00", "20:00:00"
];
let verifiReserva = document.querySelector(".verifiReserva");

fDeseada.addEventListener('change', ()=>
{
    llamarApi(fDeseada.value);
})

function llamarApi(fecha)
{
    fetch("/reservaDia/"+fecha)
    .then( reservas => 
    {
        return reservas.json();
    })
    .then(datos => 
    {
        calendario.innerHTML = "";
        let tr = document.createElement("tr");
        horas.forEach(hora => {
            let tdHora = document.createElement("th");
            tdHora.innerHTML = hora;
            tr.appendChild(tdHora)
        });
        calendario.appendChild(tr);
        Object.keys(datos).forEach(key => {
            const pista = datos[key];
            let tr = document.createElement("tr");
            let pistaNombre = document.createElement("td");
            pistaNombre.innerHTML = key
            tr.appendChild(pistaNombre);
            pista.forEach(hora => {
                let td = document.createElement("td");
                td.className = hora["estado"];
                td.innerHTML = hora["estado"];
                tr.appendChild(td);
            });
            calendario.appendChild(tr);
        });
        document.querySelectorAll('.Libre').forEach(estado => {
            estado.addEventListener('click', ()=> {
                if(estado.style.backgroundColor === "blue")
                {
                    estado.style.backgroundColor = "white";
                }
                else
                {
                    estado.style.backgroundColor = "blue";
                    let fechaPR = document.createElement("input");
                    fechaPR.type = "dateTime";
                    verifiReserva.appendChild(fechaPR);
                }
            })
        })
    })
}