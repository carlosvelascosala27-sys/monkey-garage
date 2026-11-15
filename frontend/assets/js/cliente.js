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




