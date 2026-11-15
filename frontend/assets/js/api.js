async function llamarApi(accion, datos, token = null, url = '/monkey-garage/backend/auth_sw.php' ) {
    const cabeceras = { 'Content-Type': 'application/json'};
    

    const respuesta = await fetch(url,{
        method: 'POST',
        headers: cabeceras,
        body: JSON.stringify({action: accion, auth_key: token, ...datos})
    });

    return await respuesta.json();

}