<?php 
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/model/Usuario.php';
require_once __DIR__ . '/model/Cliente.php';
require_once __DIR__ . '/model/Factura.php';


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

$dni = $buscarLogin['dni'];


switch($accion){
    case 'listar_facturas':
        $facturas = Factura::getFacturasPorCliente($dni);
        echo json_encode([
            "success" => true,
            "msg" => "Datos de las facturas obtenidos correctamente.",
            "data" => $facturas,
            
        ]);
        break;
}