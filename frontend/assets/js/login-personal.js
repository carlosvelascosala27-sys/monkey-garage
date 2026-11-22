const id_personal = document.getElementById("form-login");
id_personal.addEventListener('submit', function(e){
    e.preventDefault();

    const codigo = document.getElementById("codigo").value.trim();
    const password = document.getElementById("password").value;
    const errores = document.getElementById("errores");
    llamarApi("login", {login: codigo, password: password, panel: "empresa"}, null, "/monkey-garage/backend/auth_sw.php")
        .then (function(respuesta){
            errores.textContent = "";
            if (respuesta.success === false){
                errores.textContent = respuesta.msg;
                return;
            }

            const rutas = {oficina: "../oficina/", mecanico: "../mecanico/", admin: "../admin/"};

            if (!rutas[respuesta.data.rol]){
                errores.textContent = "Usuario no autorizado";
                return;
            }

            localStorage.setItem("token", respuesta.data.token);
            window.location.href = rutas[respuesta.data.rol];
        })


})