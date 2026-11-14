<?php 
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/model/Usuario.php';
require_once __DIR__ . '/model/Cliente.php';
require_once __DIR__ . '/model/Vehiculo.php';



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

if ($buscarLogin === false) {
    echo json_encode([
        "success" => false,
        "msg" => "No se encontró el cliente asociado a este usuario",
        "data" => []
    ]);
    exit;
}

$dni = $buscarLogin['dni'];

switch ($accion){
    case 'mis_vehiculos':
        $vehiculos = Vehiculo::getVehiculo($dni);
        echo json_encode([
            "success" => true,
            "msg" => "Datos de los vehiculos obtenidos correctamente.",
            "data" => $vehiculos
            
        ]);
        break;

    case 'editar_vehiculos':
        $matricula = $datos['matricula'];
        $vehiculo = Vehiculo::comprobarVehiculo($matricula);
        if ($vehiculo === false){
            echo json_encode([
            "success" => false,
            "msg" => "El vehiculo no existe",
            "data" => []
        ]);
        exit;
        }else{
            if ($vehiculo['cliente_dni'] != $dni){
                echo json_encode([
                "success" => false,
                "msg" => "El vehiculo no existe",
                "data" => []
            ]);
            exit;  
            }

            $datos_vehiculo = new Vehiculo ($vehiculo['matricula'], $vehiculo['cliente_dni'], $datos['marca'], $datos['modelo'], $datos['anio'], $datos['color'], $datos['combustible'], $datos['transmision'], $datos['kilometros']);
            $actualizar = $datos_vehiculo->update();
            if($actualizar === true){
                echo json_encode([
                "success" => true,
                "msg" => "Los datos del vehiculo se han actualizado",
                "data" => []
            ]);
            exit;

            }else{
                echo json_encode([
                "success" => false,
                "msg" => "El vehiculo no se ha podido actualizar.",
                "data" => []
            ]);
            exit;
            }
        }
        
        break;

    case 'borrar_vehiculos':
        $matricula = $datos['matricula'];
        $vehiculo = Vehiculo::comprobarVehiculo($matricula);
        if ($vehiculo === false){
            echo json_encode([
            "success" => false,
            "msg" => "El vehiculo no existe",
            "data" => []
        ]);
        exit;
        }else{
            if ($vehiculo['cliente_dni'] != $dni){
                echo json_encode([
                "success" => false,
                "msg" => "El vehiculo no existe",
                "data" => []
            ]);
            exit;  
            }

            $datos_vehiculo = new Vehiculo ($vehiculo['matricula'], $vehiculo['cliente_dni'], $vehiculo['marca'], $vehiculo['modelo']);
            $borrar = $datos_vehiculo->delete();
            if($borrar === true){
                echo json_encode([
                "success" => true,
                "msg" => "Los datos del vehiculo se han borrado",
                "data" => []
            ]);
            exit;

            }else{
                echo json_encode([
                "success" => false,
                "msg" => "El vehiculo no se ha podido borrar",
                "data" => []
            ]);
            exit;
            }
        }
        
        break;
    default:
        echo json_encode([
            "success" => false,
            "msg" => "Acción no válida.",
            "data" => []
        ]);
        break;
        
}


