async function llamarApi(accion, datos, token = null) {
    const cabeceras = { 'Content-Type': 'application/json'};
    

    const respuesta = await fetch('/monkey-garage/backend/auth_sw.php',{
        method: 'POST',
        headers: cabeceras,
        body: JSON.stringify({action: accion, auth_key: token, ...datos})
    });

    return await respuesta.json();




    
}