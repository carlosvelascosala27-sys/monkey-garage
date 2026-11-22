const token = localStorage.getItem("token");
const URL_MECANICO = "/monkey-garage/backend/mecanico_sw.php";
let citaActual = '';

// Crea un elemento con texto y lo añade al padre (copiada de cliente.js)
function crearElemento(etiqueta, texto, padre){
    const elemento = document.createElement(etiqueta);
    elemento.textContent = texto;
    padre.appendChild(elemento);
    return elemento;
}

// Pasa "2026-11-18 10:00:00" a "18/11, 10:00"
function formatearFecha(fecha){
    const partes = fecha.split(" ");
    const dia = partes[0].split("-");
    return dia[2] + "/" + dia[1] + ", " + partes[1].slice(0, 5);
}

function cargarCitas(){
    llamarApi("listar_citas", {}, token, URL_MECANICO)
        .then(function(respuesta){
            if(respuesta.success === false){
                return;
            }

            const lista = document.getElementById("lista-trabajos");
            lista.innerHTML = "";

            const estados = ["pendiente", "confirmada", "en_reparacion", "finalizada"];

            respuesta.data.forEach(cita => {
                const li = crearElemento("li", "", lista);

                const info = crearElemento("div", "", li);
                crearElemento("strong", cita.vehiculo_matricula, info);
                const detalle = crearElemento("span", formatearFecha(cita.fecha) + ", " + cita.motivo, info);
                detalle.className = "secundario";

                // Barra de 4 pasos: se marcan como hechos hasta el estado actual
                const pasos = crearElemento("div", "", li);
                pasos.className = "pasos";
                const posicion = estados.indexOf(cita.estado);
                for(let i = 0; i < estados.length; i++){
                    const paso = crearElemento("span", "", pasos);
                    if(i <= posicion){
                        paso.className = "hecho";
                    }
                }
            });

            // Primero la cita en reparación; si no hay, la próxima confirmada
            const enReparacion = respuesta.data.find(cita => cita.estado === "en_reparacion");
            const confirmadas = respuesta.data.filter(cita => cita.estado === "confirmada");
            const proxima = confirmadas[confirmadas.length - 1];
            pintarHoja(enReparacion ?? proxima);

            pintarHoja(enCurso);

        });
}

cargarCitas();
cargarRepuestos();

// Pinta en la hoja de trabajo la cita que se está reparando
function pintarHoja(cita){
    const vacia = document.getElementById("hoja-vacia");
    const contenido = document.getElementById("hoja-contenido");

    if(!cita){
        vacia.removeAttribute("hidden");
        contenido.setAttribute("hidden", "");
        return;
    }

    citaActual = cita;

    vacia.setAttribute("hidden", "");
    contenido.removeAttribute("hidden");

    document.getElementById("hoja-cita").textContent = "Cita #" + cita.id_cita + " · " + formatearFecha(cita.fecha);
    document.getElementById("hoja-motivo").textContent = cita.motivo;
    document.getElementById("hoja-matricula").textContent = cita.vehiculo_matricula;
    document.getElementById("hoja-estado").textContent = cita.estado.replace("_", " ");
    document.getElementById("horas-trabajadas").textContent = Number(cita.horas_trabajadas ?? 0) + " h";

    // Barra de progreso: se marcan los pasos hasta el estado actual
    const estados = ["pendiente", "confirmada", "en_reparacion", "finalizada"];
    const posicion = estados.indexOf(cita.estado);
    document.querySelectorAll("#hoja-progreso li").forEach(function(li){
        if(estados.indexOf(li.dataset.estado) <= posicion){
            li.classList.add("activo");
        } else {
            li.classList.remove("activo");
        }
    });
    // El botón y los controles cambian según el estado
    const enCurso = cita.estado === "en_reparacion";
    document.getElementById("btn-finalizar").textContent = enCurso ? "Marcar como finalizada" : "Empezar reparación";
    document.getElementById("btn-sumar-horas").hidden = !enCurso;
    document.querySelector(".anadir-pieza").hidden = !enCurso;
    cargarPiezas(cita.id_cita);

}


// Pasa 12.5 a "12,50 €" (copiada de cliente.js)
function formatearDinero(valor){
    return Number(valor).toFixed(2).replace(".", ",") + " €";
}

// Carga y pinta las piezas de una cita en la tabla de la hoja
function cargarPiezas(idCita){
    llamarApi("listar_piezas", {id_cita: idCita}, token, URL_MECANICO)
        .then(function(respuesta){
            if(respuesta.success === false){
                return;
            }

            const tabla = document.getElementById("hoja-piezas");
            tabla.innerHTML = "";
            let total = 0;

            respuesta.data.piezas.forEach(pieza => {
                const importe = Number(pieza.cantidad) * Number(pieza.precio_unitario);
                total = total + importe;

                const tr = crearElemento("tr", "", tabla);
                crearElemento("td", pieza.nombre, tr);
                crearElemento("td", pieza.cantidad, tr).className = "numero";
                crearElemento("td", formatearDinero(importe), tr).className = "numero";
            });

            document.getElementById("hoja-materiales").textContent = formatearDinero(total);
        });
}

// Rellena el desplegable con el catálogo de repuestos
function cargarRepuestos(){
    llamarApi("listar_repuestos", {}, token, URL_MECANICO)
        .then(function(respuesta){
            if(respuesta.success === false){
                return;
            }

            const select = document.getElementById("select-repuesto");
            select.innerHTML = "";

            respuesta.data.repuestos.forEach(repuesto => {
                const opcion = crearElemento("option", repuesto.nombre + " - " + formatearDinero(repuesto.precio), select);
                opcion.value = repuesto.id_repuesto;
            });
        });
}

// Añade la pieza elegida a la cita de la hoja
document.getElementById("btn-anadir-pieza").addEventListener("click", function(){
    const idRepuesto = document.getElementById("select-repuesto").value;
    const cantidad = document.getElementById("cantidad-repuesto").value;
    const mensaje = document.getElementById("mensaje-hoja");

    llamarApi("añadir_pieza", {id_cita: citaActual.id_cita, id_repuesto: idRepuesto, cantidad: cantidad}, token, URL_MECANICO)
        .then(function(respuesta){
            mensaje.textContent = respuesta.msg;
            if(respuesta.success === true){
                cargarPiezas(citaActual.id_cita);
            }
        });
});

// Suma media hora a la cita de la hoja
document.getElementById("btn-sumar-horas").addEventListener("click", function(){
    const mensaje = document.getElementById("mensaje-hoja");

    llamarApi("imputar_horas", {id_cita: citaActual.id_cita, horas: 0.5}, token, URL_MECANICO)
        .then(function(respuesta){
            mensaje.textContent = respuesta.msg;
            if(respuesta.success === true){
                document.getElementById("horas-trabajadas").textContent = Number(respuesta.data.horas_trabajadas) + " h";
            }
        });
});

// Pasa la cita de la hoja a "finalizada"
document.getElementById("btn-finalizar").addEventListener("click", function(){
    const mensaje = document.getElementById("mensaje-hoja");
    const nuevoEstado = citaActual.estado === "confirmada" ? "en_reparacion" : "finalizada";
    llamarApi("cambiar_estado", {id_cita: citaActual.id_cita, estado: nuevoEstado}, token, URL_MECANICO)

        .then(function(respuesta){
            mensaje.textContent = respuesta.msg;
            if(respuesta.success === true){
                cargarCitas();
            }
        });
});


// Nombre, login e iniciales del mecánico en el sidebar y en el saludo
function cargarDatosMecanico(){
    llamarApi("mis_datos", {}, token, URL_MECANICO)
        .then(function(respuesta){
            // Si el token no vale (sesión caducada o rol incorrecto), vuelta al login
            if(respuesta.success === false){
                window.location.href = "../personal/";
                return;
            }

            const nombre = respuesta.data.nombre;
            document.getElementById("usuario-nombre").textContent = nombre;
            document.getElementById("nombre-usuario").textContent = nombre;
            document.getElementById("usuario-login").textContent = respuesta.data.login;

            // Iniciales: primera letra de cada palabra del nombre, máximo dos
            const iniciales = nombre.split(" ").map(palabra => palabra.charAt(0)).join("").slice(0, 2);
            document.getElementById("usuario-avatar").textContent = iniciales.toUpperCase();
        });
}

// Cerrar sesión: borra el token en el servidor y en el navegador
document.querySelector(".cerrar-sesion").addEventListener("click", function(){
    llamarApi("logout", {}, token, "/monkey-garage/backend/auth_sw.php")
        .then(function(){
            localStorage.removeItem("token");
            window.location.href = "../personal/";
        });
});

// Pestañas del sidebar: cada enlace muestra su sección; "Mi jornada" muestra todo
function iniciarPestanas(){
    const enlaces = document.querySelectorAll(".sidebar nav a");
    const secciones = document.querySelectorAll("section[data-seccion]");

    function mostrarSeccion(nombre){
        secciones.forEach(seccion => {
            if(nombre === "resumen" || seccion.dataset.seccion === nombre){
                seccion.removeAttribute("hidden");
            } else {
                seccion.setAttribute("hidden", "");
            }
        });
    }

    enlaces.forEach(enlace => {
        enlace.addEventListener("click", function(evento){
            evento.preventDefault();
            enlaces.forEach(e => e.classList.remove("activo"));
            enlace.classList.add("activo");
            mostrarSeccion(enlace.dataset.seccion);
        });
    });

    mostrarSeccion("resumen");
}

cargarDatosMecanico();
iniciarPestanas();