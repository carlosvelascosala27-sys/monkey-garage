const formulario = document.querySelector("form");
formulario.addEventListener('submit', async (e) => {
    e.preventDefault();
    const nombre = document.querySelector('#nombre').value;
    const apellidos = document.querySelector('#apellidos').value;
    const telefono = document.querySelector('#telefono').value;
    const dni = document.querySelector('#dni').value;
    const email = document.querySelector('#email').value;
    const password = document.querySelector('#password').value;
    if (nombre.trim() === '' || apellidos.trim() === '' || telefono.trim() === '' || dni.trim() === '' || email.trim() === '' || password.trim() === '') {
        document.querySelector('#errores').textContent = 'Por favor, complete todos los campos.';
        document.querySelector('#errores');
        return;
    }
    const rpassword = document.querySelector('#rpassword').value;
    if (password !== rpassword) {
        document.querySelector('#errores').textContent = 'Las contraseñas no coinciden.';
        return;
    }
    const respuesta = await llamarApi('registro', {
        nombre: nombre,
        apellidos: apellidos,
        telefono: telefono,
        dni: dni,
        login: email,
        password: password,
    });

    if (respuesta.success == true) {
        const respuestaLogin = await llamarApi('login', {
            login: email,
            password: password,
        });
        if (respuestaLogin.success == true) {
            localStorage.setItem('token', respuestaLogin.data.token);
            window.location.href = '../cliente/';
        }else {
            document.querySelector('#errores').textContent = respuestaLogin.msg;
        }
    }else {
        document.querySelector('#errores').textContent = respuesta.msg;
    }

    
});