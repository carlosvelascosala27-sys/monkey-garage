const tokenCitas = localStorage.getItem("token");
let paginaActual = 1;
let totalCitas = 0;
let mesMostrado = new Date().getMonth();
let anioMostrado = new Date().getFullYear();
let fechasOcupadas = [];
document.querySelector(".btn-nueva-cita").addEventListener("click", abrirFormularioCita);
document.querySelector(".btn-añadir").addEventListener("click", abrirFormularioCita);
document.getElementById("cerrar-modal-cita").addEventListener("click", cerrarFormularioCita);
if (tokenCitas === null){
    window.location.href ="../index.html"

}else{
    cargarCitas(tokenCitas);

    
     
}

function cargarCitas(tokenCitas){
    

    return llamarApi("listar_citas", {pagina: paginaActual}, tokenCitas, "/monkey-garage/backend/cita_sw.php")
        .then(function (respuesta){

            if(respuesta.success === false){
                mostrarMensajeCitas(respuesta.msg);
                return;
            }
            totalCitas = respuesta.total;
            mostrarMensajeCitas("");

            const listaCita = document.querySelector(".lista-citas");
            listaCita.innerHTML = "";
            if (respuesta.data.length === 0){
                mostrarMensajeCitas("No tienes citas"); 
            }else{
                respuesta.data.forEach(cita => {
                    listaCita.appendChild(crearTarjetaCita(cita));

                    
                });
            }

            pintarPaginador();


        })



        

}
function mostrarMensajeCitas(texto){
    document.getElementById("mensaje-citas").textContent = texto;
}
const meses = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"];

function crearTarjetaCita(cita){
    const datosCitas = document.createElement("article");
    datosCitas.className = "cita";

    const divFecha = document.createElement("div");
    divFecha.className = "fecha";
    const mes = document.createElement("small");
    const dia = document.createElement("strong");
    const soloFecha = cita.fecha.split(" ")[0];
    const partes = soloFecha.split("-");

    mes.textContent = meses[parseInt(partes[1]-1)];
    dia.textContent = partes[2];

    datosCitas.appendChild(divFecha);
    divFecha.appendChild(mes);
    divFecha.appendChild(dia);

    const anio = parseInt(partes[0]);
    if (anio !== new Date().getFullYear()){
        const year = document.createElement("small");
        year.textContent = anio;
        divFecha.appendChild(year);
    }

    const info = document.createElement("div");
    info.className = "info-cita";
    const motivo = document.createElement("strong");
    motivo.textContent = cita.motivo;
    info.appendChild(motivo);
    const matricula = document.createElement("div");
    matricula.className = "vehiculo-cita";
    const span = document.createElement("span");
    span.className = "matricula";
    span.textContent = cita.vehiculo_matricula;
    matricula.appendChild(span);
    info.appendChild(matricula);

    const pie = document.createElement("div");
    pie.className = "pie-cita";
    const hora = document.createElement("small");
    const soloHora = cita.fecha.split(" ")[1];
    hora.textContent = soloHora.slice(0, 5);
    const estado = document.createElement("span");
    estado.className = "estado " + cita.estado;
    estado.textContent =  cita.estado.charAt(0).toUpperCase() + cita.estado.slice(1);
    pie.appendChild(hora);
    pie.appendChild(estado);
    info.appendChild(pie);
    datosCitas.appendChild(info);
    return datosCitas;

}

function abrirFormularioCita(){
    p.textContent = "";
    cargarVehiculosFormulario();
    document.getElementById("modal-cita").style.display = "flex";
    llamarApi("horas_ocupadas", {}, tokenCitas, "/monkey-garage/backend/cita_sw.php")
        .then(function (respuesta){
            if(respuesta.success === false){
                mostrarMensajeCitas(respuesta.msg);
                return;
            }

            fechasOcupadas = respuesta.data;

        })

}

function cerrarFormularioCita(){
    document.getElementById("modal-cita").style.display = "none";

}

const contenidoCita = document.getElementById("contenido-cita");
const calendario = document.createElement("div");
calendario.className = "popup-dias";
calendario.style.display = "none";
document.body.appendChild(calendario);

function crearCalendario(){
    calendario.innerHTML = "";

    const hoy = new Date();
    const hoySinHora = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate());
    const nombresMeses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    const cabecera = document.createElement("div");
    cabecera.className = "cabecera-popup";

    const anterior = document.createElement("button");
    anterior.type = "button";
    anterior.textContent = "‹";
    anterior.disabled = (anioMostrado === hoy.getFullYear() && mesMostrado === hoy.getMonth());
    anterior.addEventListener("click", function(){
        mesMostrado--;
        if (mesMostrado < 0){
            mesMostrado = 11;
            anioMostrado--;
        }
        crearCalendario();
    });

    const titulo = document.createElement("p");
    titulo.textContent = nombresMeses[mesMostrado] + " " + anioMostrado;

    const siguiente = document.createElement("button");
    siguiente.type = "button";
    siguiente.textContent = "›";
    siguiente.addEventListener("click", function(){
        mesMostrado++;
        if (mesMostrado > 11){
            mesMostrado = 0;
            anioMostrado++;
        }
        crearCalendario();
    });

    const cerrar = document.createElement("button");
    cerrar.type = "button";
    cerrar.textContent = "X";
    cerrar.addEventListener("click", function(){
        calendario.style.display = "none";
    });

    cabecera.appendChild(anterior);
    cabecera.appendChild(titulo);
    cabecera.appendChild(siguiente);
    cabecera.appendChild(cerrar);
    calendario.appendChild(cabecera);

    const dias = document.createElement("div");
    dias.className = "dias";
    calendario.appendChild(dias);

    const diasDelMes = new Date(anioMostrado, mesMostrado + 1, 0).getDate();
    const primerDia = new Date(anioMostrado, mesMostrado, 1).getDay();
    const huecos = (primerDia === 0 || primerDia === 6) ? 0 : primerDia - 1;

    for (let h = 0; h < huecos; h++){
        dias.appendChild(document.createElement("span"));
    }

    for (let dia = 1; dia <= diasDelMes; dia++){
        const fecha = new Date(anioMostrado, mesMostrado, dia);
        const diaSemana = fecha.getDay();
        if (diaSemana === 0 || diaSemana === 6){
            continue;
        }

        const textoFecha = anioMostrado + "-" + String(mesMostrado + 1).padStart(2, "0") + "-" + String(dia).padStart(2, "0");
        const botonDia = document.createElement("button");
        botonDia.type = "button";
        botonDia.textContent = dia;

        const ocupado = fechasOcupadas.some(cita => cita.fecha.split(" ")[0] === textoFecha);
        const pasado = fecha < hoySinHora;
        if (ocupado || pasado){
            botonDia.disabled = true;
        }
        botonDia.addEventListener("click", function(){
            date.value = textoFecha;
            time.value = "";
            calendario.style.display = "none";
            abrirHoras();
        });

        dias.appendChild(botonDia);

        
        
    }
    

}

const popupHoras = document.createElement("div");
popupHoras.className = "popup-horas";
popupHoras.style.display = "none";
document.body.appendChild(popupHoras);

const HORAS_DISPONIBLES = ["09:00", "10:00", "11:00", "12:00", "13:00", "16:00", "17:00", "18:00"];

function crearHoras(){
    popupHoras.innerHTML = "";

    const cabecera = document.createElement("div");
    cabecera.className = "cabecera-horas";

    const titulo = document.createElement("p");
    titulo.textContent = date.value;

    const cerrar = document.createElement("button");
    cerrar.type = "button";
    cerrar.textContent = "X";
    cerrar.addEventListener("click", function(){
        popupHoras.style.display = "none";
    });

    cabecera.appendChild(titulo);
    cabecera.appendChild(cerrar);
    popupHoras.appendChild(cabecera);

    const rejilla = document.createElement("div");
    rejilla.className = "horas";
    popupHoras.appendChild(rejilla);

    const hoy = new Date();
    const hoyTexto = hoy.getFullYear() + "-" + String(hoy.getMonth() + 1).padStart(2, "0") + "-" + String(hoy.getDate()).padStart(2, "0");
    const horaActual = String(hoy.getHours()).padStart(2, "0") + ":" + String(hoy.getMinutes()).padStart(2, "0");

    HORAS_DISPONIBLES.forEach(function(hora){
        const botonHora = document.createElement("button");
        botonHora.type = "button";
        botonHora.textContent = hora;

        const ocupada = fechasOcupadas.some(cita => cita.fecha.split(" ")[0] === date.value && cita.fecha.split(" ")[1].slice(0, 5) === hora);
        const pasada = date.value === hoyTexto && hora <= horaActual;
        if (ocupada || pasada){
            botonHora.disabled = true;
        }

        botonHora.addEventListener("click", function(){
            time.value = hora;
            popupHoras.style.display = "none";
        });

        rejilla.appendChild(botonHora);
    });
}

function abrirHoras(){
    crearHoras();
    popupHoras.style.display = "block";
}

const form = document.createElement("form");
const date = document.createElement("input");
const time = document.createElement("input");
const texto = document.createElement("input");
const boton = document.createElement("button");
const select = document.createElement("select");
date.type = "date";
date.name = "fecha";
time.type = "time";
time.name = "hora";
texto.type = "text";
texto.name = "motivo";
texto.placeholder = "Motivo de la cita";
boton.type = "submit";
boton.textContent = "Pedir Cita";
select.name = "matricula";
select.className = "select-vehiculo";
date.readOnly = true;
time.readOnly = true;
date.addEventListener("click", function(){
    crearCalendario();
    calendario.style.display = "block";
});
form.appendChild(select);

form.appendChild(date);
time.addEventListener("click", function(){
    if (date.value === ""){
        crearCalendario();
        calendario.style.display = "block";
    }else{
        abrirHoras();
    }
});
form.appendChild(time);
form.appendChild(texto);
form.appendChild(boton);
contenidoCita.appendChild(form);
form.addEventListener("submit", function(e){
    e.preventDefault();
    const valueSelect = select.value;
    const valueDate = date.value;
    const valueTime = time.value;
    const valueTexto = texto.value;

    const fechaCompleta = valueDate + " " + valueTime + ":00";
    p.textContent = "";
    llamarApi("pedir_cita", {matricula: valueSelect, fecha: fechaCompleta, motivo: valueTexto}, tokenCitas, "/monkey-garage/backend/cita_sw.php")
        .then(function(respuesta){
            if(respuesta.success === true){
                cargarCitas(tokenCitas).then(function(){
                    (mostrarMensajeCitas(respuesta.msg));

                }) 
;
                cerrarFormularioCita();
                
            }else{
                p.textContent = respuesta.msg;
            }
        })

})

const p = document.createElement("p");
form.appendChild(p);

function cargarVehiculosFormulario(){
    llamarApi("mis_vehiculos", {}, tokenCitas, "/monkey-garage/backend/vehiculo_sw.php")
        .then(function(respuesta){
            if(respuesta.success === false){
                p.textContent = respuesta.msg;
                return;
            }

            select.innerHTML = "";

            if (respuesta.data.length === 0){
                p.textContent = "No tienes vehiculos";
                return;

            }else{
                respuesta.data.forEach(vehiculo => {
                    const option = document.createElement("option");
                    option.value = vehiculo.matricula;
                    option.textContent = vehiculo.marca + " " + vehiculo.modelo;
                    select.appendChild(option);

                })
            }

            

            
            


        })

}


function calcularPaginas(){
    return Math.ceil(totalCitas/4);
}

function pintarPaginador(){
    const paginador = document.querySelector(".paginador");
    paginador.innerHTML = "";
    const anterior = document.createElement("button");
    anterior.textContent = "Anterior";
    if(paginaActual === 1){
        anterior.disabled = true;
    }
    paginador.appendChild(anterior);

    anterior.addEventListener("click", function(){
        paginaActual--;
        cargarCitas(tokenCitas);
    });

    

    

    for (let i = 1; i <= calcularPaginas(); i++){
    const botonPagina = document.createElement("button");
    botonPagina.textContent = i;
    botonPagina.addEventListener("click", function(){
        paginaActual = i;
        cargarCitas(tokenCitas);
    });
    if (i === paginaActual){
        botonPagina.className = "activo";
    }
    paginador.appendChild(botonPagina);
    }

    const siguiente = document.createElement("button");
    siguiente.textContent = "Siguiente";
    if(paginaActual >= calcularPaginas()){
        siguiente.disabled = true;
    }
    paginador.appendChild(siguiente);
    siguiente.addEventListener("click", function(){
        paginaActual++;
        cargarCitas(tokenCitas);
    });

}


