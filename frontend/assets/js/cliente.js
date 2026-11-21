const token = localStorage.getItem("token");
const modalEditar = document.getElementById("modal-editar");
const modalContenido = document.getElementById("contenido-modal");
const modalCerrar = document.getElementById("cerrar-modal");

modalCerrar.addEventListener('click', function(){
                    modalEditar.style.display = "none"
})




if (token === null){
    window.location.href ="../index.html"

}else{
    cargarVehiculos(token);
    
     
}

function cargarVehiculos(token){
    llamarApi("mis_vehiculos", {}, token, "/monkey-garage/backend/vehiculo_sw.php")

        .then (function(respuesta){
            
            const listaVehiculo = document.querySelector(".lista-vehiculos");
            listaVehiculo.innerHTML = "";

            if(respuesta.success === false){
                mostrarMensaje(respuesta.msg);
                return;
            }
            
            if (respuesta.data.length === 0){
                const p = document.createElement("p");
                p.textContent = "No tienes vehiculos";
                listaVehiculo.appendChild(p); 
            }else{
                

                respuesta.data.forEach(vehiculo => {
                    const datosVehiculo = document.createElement("article");
                    datosVehiculo.className = "vehiculo";
                    const cabecera = document.createElement("header");
                    cabecera.className = "vehiculo-cabecera";
                    datosVehiculo.appendChild(cabecera);
                    const h3 = document.createElement("h3");
                    datosVehiculo.appendChild(h3);
                    const matricula =  crearElemento("span", vehiculo.matricula, cabecera);
                    matricula.className = "matricula";
                    crearElemento("span", vehiculo.marca + " " + vehiculo.modelo, h3);
                    listaVehiculo.appendChild(datosVehiculo);


                    const contenedor = document.createElement("div");
                    contenedor.className = "datos";
                    crearDato("Año", vehiculo.anio, contenedor);
                    crearDato("Combustible", capitalizar(vehiculo.combustible), contenedor);
                    crearDato("Transmisión", capitalizar(vehiculo.transmision), contenedor);
                    crearDato("Kilómetros", vehiculo.kilometros, contenedor);
                    datosVehiculo.appendChild(contenedor);

                    const footer = document.createElement("footer");
                    footer.className = "vehiculo-pie";
                    datosVehiculo.appendChild(footer);
                    
                    const botonBorrar = document.createElement("button");
                    botonBorrar.textContent = "Borrar";
                    footer.appendChild(botonBorrar);

                    const botonEditar = document.createElement("button");
                    botonEditar.textContent = "Editar";
                    footer.appendChild(botonEditar);

                    botonBorrar.addEventListener('click', function(){
                        mostrarMensaje("");
                        llamarApi("borrar_vehiculos", {matricula: vehiculo.matricula}, token, "/monkey-garage/backend/vehiculo_sw.php")
                        .then (function(respuesta){
                            mostrarMensaje(respuesta.msg);
                            cargarVehiculos(token);
                        })


                    })

                    const formularioEditar = crearFormulario(vehiculo, datosVehiculo);
                    botonEditar.addEventListener('click', function(){
                        modalContenido.innerHTML = "";
                        formularioEditar.reset();
                        modalContenido.appendChild(formularioEditar);
                        formularioEditar.style.display = "block";
                        modalEditar.style.display = "flex";
                        
                        
                        



                    })

                    formularioEditar.addEventListener('submit', function(e){
                        e.preventDefault();
                        const campos = formularioEditar.querySelectorAll("input");

                        const datos = {
                            marca: campos[0].value,
                            modelo: campos[1].value,
                            anio: campos[2].value,
                            color: campos[3].value,
                            combustible: campos[4].value,
                            transmision: campos[5].value,
                            kilometros: campos[6].value,
                            matricula: vehiculo.matricula
                        };
                        llamarApi("editar_vehiculos", datos, token, "/monkey-garage/backend/vehiculo_sw.php")
                        .then (function(respuesta){
                            mostrarMensaje(respuesta.msg);
                            modalEditar.style.display = "none";
                            cargarVehiculos(token);
                        })

                    })
                    


                    

                    


                    






                    
                });

                
            }
            
        });
}
function crearElemento(etiqueta, texto, padre){
    const elemento = document.createElement(etiqueta);
    elemento.textContent = texto;
    padre.appendChild(elemento);
    return elemento;
}

function crearDato(etiqueta, valor, padre){
    const bloque = document.createElement("div");
    const p = document.createElement("p");
    p.className = "etiqueta";
    p.textContent = etiqueta;
    bloque.appendChild(p);
    crearElemento("strong", valor, bloque);
    padre.appendChild(bloque);   
}

function capitalizar(texto){
    return texto.charAt(0).toUpperCase() + texto.slice(1);
}

function mostrarMensaje(texto){
    document.getElementById("mensaje-vehiculos").textContent = texto;
}

function crearFormulario(vehiculo, padre){
    const formulario = document.createElement("form");
    formulario.style.display = "none";

    crearCampo("Marca", vehiculo.marca, formulario);
    crearCampo("Modelo", vehiculo.modelo, formulario);
    crearCampo("Año", vehiculo.anio, formulario);
    crearCampo("Color", vehiculo.color, formulario);
    crearCampo("Combustible", vehiculo.combustible, formulario);
    crearCampo("Transmisión", vehiculo.transmision, formulario);
    crearCampo("Kilómetros", vehiculo.kilometros, formulario);

    const botonGuardar = document.createElement("button");
    botonGuardar.textContent = "Guardar"
    botonGuardar.type = "submit";
    formulario.appendChild(botonGuardar);


    
    padre.appendChild(formulario);
    return formulario;
}

function crearCampo(etiqueta, valor, padre){
    const label = document.createElement("label");
    const input = document.createElement("input");
    label.textContent = etiqueta;
    input.defaultValue = valor;
    label.appendChild(input);
    padre.appendChild(label);
}

function cargarNombreCliente(){
    const tokenPanel = localStorage.getItem("token");
    llamarApi("mis_datos", {}, tokenPanel, "/monkey-garage/backend/cliente_sw.php")
        .then(function(respuesta){
            if(respuesta.success === false){
                return;
            }
            document.getElementById("nombre-cliente").textContent = respuesta.data.nombre;
        });
}

cargarNombreCliente();

const logout = document.querySelector(".cerrar-sesion");
logout.addEventListener("click", function(){
    llamarApi("logout", {}, token, "/monkey-garage/backend/auth_sw.php")
        .then(function(respuesta){
            localStorage.removeItem("token");
            window.location.href="../index.html"

            
        });

})

function cargarReparacion(){
    llamarApi("reparacion_en_curso", {}, token, "/monkey-garage/backend/cita_sw.php")
        .then(function(respuesta){

            if(respuesta.success === false){
                return;
            }
            if(respuesta.data.length === 0){
                document.getElementById("rep-contenido").setAttribute("hidden", "");
                document.getElementById("rep-vacio").removeAttribute("hidden");
                return;
            
            }

           const datos = respuesta.data;

            document.getElementById("rep-cita").textContent = datos.fecha.slice(0,16);
            const estado = datos.estado.replace("_"," ");
            document.getElementById("rep-estado").textContent = capitalizar(estado);

            const estados = ["pendiente", "confirmada", "en_reparacion", "finalizada"];
            const posicionActual = estados.indexOf(datos.estado);

            document.querySelectorAll("#rep-progreso li").forEach(function(paso,indice){
                if (indice <= posicionActual){
                    paso.classList.add("activo");
                }else{
                    paso.classList.remove("activo");
                }
            })

            const lista = document.getElementById("rep-piezas");
            lista.innerHTML = "";
            if(datos.piezas.length === 0){
                lista.textContent = "Sin piezas registradas"
            }else{
                datos.piezas.forEach(function(pieza){
                    const li = document.createElement("li");
                    li.textContent = pieza.nombre + " - " + pieza.cantidad + " - " + pieza.precio_unitario + " €";
                    lista.appendChild(li);
                    
                })
            }

            if(datos.mecanico_login === null){
                document.getElementById("rep-mecanico").textContent = "De momento no hay asignación de mecánico";   // texto cuando no hay mecánico
            }else{
                document.getElementById("rep-mecanico").textContent = datos.mecanico_login;   // el login cuando sí hay mecánico
            }
                

            document.getElementById("rep-matricula").textContent = datos.matricula;
            document.getElementById("rep-motivo").textContent = "Motivo: " + datos.motivo;
            document.getElementById("rep-horas").textContent = datos.horas_trabajadas + " h";
            document.getElementById("rep-materiales").textContent = Number(datos.total_materiales).toFixed(2) + " €";

        });

}

cargarReparacion();

function formatearDinero(valor){
    return Number(valor).toFixed(2).replace(".",",") + " €"
}



function cargarFacturas(){
    llamarApi("listar_facturas", {}, token, "/monkey-garage/backend/factura_sw.php")
        .then(function(respuesta){
            if(respuesta.success === false){
                return;
            }

            const tabla = document.querySelector(".facturas tbody");
            tabla.innerHTML = "";

            respuesta.data.forEach(factura => {
                const tr = document.createElement("tr");

                const numero = crearElemento("td", factura.numero_factura, tr);
                numero.className = "col-extra";

                const partes = factura.fecha.split("-");
                crearElemento("td", partes[2] + "/" + partes[1] + "/" + partes[0], tr);

                crearElemento("td", factura.marca + " " + factura.modelo, tr);

                const cita = crearElemento("td", "#" + factura.id_cita, tr);
                cita.className = "col-extra";

                const manoObra = crearElemento("td", formatearDinero(factura.mano_obra), tr);
                manoObra.className = "numero col-extra";

                const repuestos = crearElemento("td", formatearDinero(Number(factura.subtotal) - Number(factura.mano_obra)), tr);
                repuestos.className = "numero col-extra";

                const subtotal = crearElemento("td", formatearDinero(factura.subtotal), tr);
                subtotal.className = "numero col-extra";

                const iva = crearElemento("td", formatearDinero(factura.iva), tr);
                iva.className = "numero col-extra";

                const total = crearElemento("td", formatearDinero(factura.total), tr);
                total.className = "numero total";

                const ver = crearElemento("td", "", tr);
                const enlaceVer = crearElemento("a", "Ver", ver);
                enlaceVer.href = "#";
                enlaceVer.addEventListener("click", function(evento){
                    evento.preventDefault();
                    imprimirFactura(factura);
                });

                tabla.appendChild(tr);
            });
        });
}

cargarFacturas();


function imprimirFactura(factura){
    const ventana = window.open("", "_blank");
    const partes = factura.fecha.split("-");
    const fecha = partes[2] + "/" + partes[1] + "/" + partes[0];
    const repuestos = Number(factura.subtotal) - Number(factura.mano_obra);

    ventana.document.write(`
        <html>
        <head>
            <title>Factura ${factura.numero_factura}</title>
            <style>
                body {
                    font-family: Arial, Helvetica, sans-serif;
                    color: #222;
                    max-width: 700px;
                    margin: 40px auto;
                    padding: 0 20px;
                }
                h1 {
                    margin-bottom: 4px;
                    color: #2e7d32;
                }
                h2 {
                    margin-top: 0;
                    font-weight: normal;
                    border-bottom: 2px solid #2e7d32;
                    padding-bottom: 10px;
                }
                .datos p {
                    margin: 6px 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 24px;
                }
                th, td {
                    padding: 10px;
                    border-bottom: 1px solid #ddd;
                    text-align: left;
                }
                .numero {
                    text-align: right;
                }
                .total td {
                    font-weight: bold;
                    font-size: 18px;
                    border-top: 2px solid #2e7d32;
                    border-bottom: none;
                }
                @media print {
                    body {
                        margin: 0;
                        max-width: none;
                    }
                }
            </style>
        </head>
        <body>
            <h1>Monkey Garage</h1>
            <h2>Factura nº ${factura.numero_factura}</h2>
            <div class="datos">
                <p><strong>Fecha:</strong> ${fecha}</p>
                <p><strong>Vehículo:</strong> ${factura.marca} ${factura.modelo}</p>
                <p><strong>Cita:</strong> #${factura.id_cita}</p>
            </div>
            <table>
                <tr><td>Mano de obra</td><td class="numero">${formatearDinero(factura.mano_obra)}</td></tr>
                <tr><td>Repuestos</td><td class="numero">${formatearDinero(repuestos)}</td></tr>
                <tr><td>Subtotal</td><td class="numero">${formatearDinero(factura.subtotal)}</td></tr>
                <tr><td>IVA (21 %)</td><td class="numero">${formatearDinero(factura.iva)}</td></tr>
                <tr class="total"><td>Total</td><td class="numero">${formatearDinero(factura.total)}</td></tr>
            </table>
        </body>
        </html>
    `);
    ventana.document.close();
    ventana.print();
}

function cargarResumen(){
    llamarApi("mis_vehiculos", {}, token, "/monkey-garage/backend/vehiculo_sw.php")
        .then(function(respuesta){
            if(respuesta.success === false){ return; }
            document.getElementById("resumen-vehiculos").textContent = respuesta.data.length;
        });

    llamarApi("resumen_citas", {}, token, "/monkey-garage/backend/cita_sw.php")
        .then(function(respuesta){
            if(respuesta.success === false){ return; }
            const conteos = respuesta.data;
            const pendientes = conteos.pendiente || 0;
            const confirmadas = conteos.confirmada || 0;
            const enReparacion = conteos.en_reparacion || 0;

            document.getElementById("resumen-citas").textContent = pendientes + confirmadas;
            document.getElementById("resumen-citas-pendientes").textContent = pendientes + " pendiente(s) de confirmar";
            document.getElementById("resumen-reparacion").textContent = enReparacion;
        });
}
cargarResumen();

function iniciarPestanas(){
    const enlaces = document.querySelectorAll(".sidebar nav a");
    const secciones = document.querySelectorAll("main > section");

    function mostrarSeccion(nombre){
        secciones.forEach(seccion => {
            if(seccion.dataset.seccion === nombre  || nombre === "resumen"){
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

iniciarPestanas();



