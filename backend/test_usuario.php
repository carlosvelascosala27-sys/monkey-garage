<?php
require_once __DIR__ . '/model/Usuario.php';

// 1. Insertar
$u = new Usuario('prueba@test.com', 'temporal', 'Prueba', 'cliente');
echo "Insert: ";
var_dump($u->insert());
echo "<br>";
// Comprobar contraseña con password_verify
echo "Verificar correcta: ";
var_dump($u->verificarPassword('temporal'));
echo "<br>";

echo "Verificar incorrecta: ";
var_dump($u->verificarPassword('otra'));
echo "<br>";

// 2. Leer (login y nombre sí, password no)
echo "Login: " . $u->login . "<br>";
echo "Nombre: " . $u->nombre . "<br>";
try {
    echo $u->password;
} catch (Exception $e) {
    echo "Excepción: " . $e->getMessage() . "<br>";
}

// 3. Modificar
$u->nombre = 'Prueba editada';
echo "Update: ";
var_dump($u->update());
echo "<br>";

// 4. Listar todos los usuarios
echo "Listado:<br>";
foreach (Usuario::getArray() as $fila) {
    echo $fila['login'] . " | " . $fila['nombre'] . " | " . $fila['rol'] . "<br>";
}

// 5. Borrar el usuario de prueba
echo "Delete: ";
var_dump($u->delete());