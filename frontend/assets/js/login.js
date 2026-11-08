const formulario = document.querySelector("form");

formulario.addEventListener("submit", async (event) => {
    event.preventDefault(); // Evitar que se recargue la página al enviar el formulario
    const email = document.querySelector("#email").value;
    const password = document.querySelector("#password").value;
    const respuesta = await llamarApi("login",{ 
        login: email,
        password: password
    });

    if (respuesta.success == true && respuesta.data.rol == "cliente") {
        // Guardar el token en el almacenamiento local del navegador
        localStorage.setItem("token", respuesta.data.token);
        // Redirigir al usuario a la página de inicio
        window.location.href = "cliente/";
    }else {
        let text = "";
        if (respuesta.success == true) {
            text = "Usuario no autorizado";
        }else {
            text = "Usuario o contraseña incorrectos";
        }
        document.querySelector("#errores").textContent = text;

    }

});