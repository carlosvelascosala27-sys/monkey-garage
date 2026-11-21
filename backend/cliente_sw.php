<?php 
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/model/Usuario.php';
require_once __DIR__ . '/model/Cliente.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "msg" => "Método no permitido.",
        "data" => []
    ]);
    exit;
}

$datos = json_decode(file_get_contents('php://input'), true);
$accion = isset($datos['action']) ? $datos['action'] : null;

$rolesPermitidos = ['cliente']; // Define los roles permitidos para acceder a este endpoint
require_once __DIR__ . '/auth.inc.php'; // Esto verificará el token y los roles permitidos

$buscarLogin = Cliente::buscarPorLogin($usuario->login);

if($buscarLogin === false){
    echo json_encode([
        "success" => false,
        "msg" => "No se encontró el cliente asociado a este usuario",
        "data" => []
    ]);
    exit;
}

switch ($accion){
    case 'mis_datos':
        echo json_encode([
            "success" => true,
            "msg" => "Datos del cliente obtenidos correctamente.",
            "data" => [
                "login" => $usuario->login,
                "nombre" => $buscarLogin['nombre'],
                "rol" => $usuario->rol
            ]
        ]);
        break;
    default:
        echo json_encode([
            "success" => false,
            "msg" => "Acción no válida.",
            "data" => []
        ]);
        break;
        
}