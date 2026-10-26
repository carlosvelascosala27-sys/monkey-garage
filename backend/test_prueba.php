<?php
require_once __DIR__ . '/model/Conexion.php';

$conexion1 = Conexion::getInstance();
$conexion2 = Conexion::getInstance();

echo $conexion1 === $conexion2 ? 'Las conexiones son iguales' : 'Las conexiones son diferentes';

$pdo = $conexion1->getConnection();
$consulta = $pdo->query("SELECT login, nombre, rol FROM usuario");

echo "<h2>Usuarios:</h2>";
while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
    echo "Login: " . $row['login'] . ", Nombre: " . $row['nombre'] . ", Rol: " . $row['rol'] . "<br>";
}

