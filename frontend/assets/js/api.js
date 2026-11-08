async function llamarApi(accion, datos, token = null) {
    const cabeceras = { 'Content-Type': 'application/json'};
    if(token !== null) {
        cabeceras['Authorization'] = 'Bearer ' + token; // Si hay token, lo añadimos a las cabeceras para identificar al usuario

    }

    // Llamada al servidor: la URL, el método POST, las cabeceras y los datos
    // La acción va dentro del JSON, junto con el login y la contraseña
    const respuesta = await fetch('/monkey-garage/backend/auth_sw.php',{
        method: 'POST',
        headers: cabeceras,
        body: JSON.stringify({action: accion, ...datos})

    });

    return await respuesta.json();




    
}