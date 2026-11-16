<?php 
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/model/Usuario.php';
require_once __DIR__ . '/model/Cliente.php';
require_once __DIR__ . '/model/Vehiculo.php';
require_once __DIR__ . '/model/Cita.php';
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
switch($accion){

case 'listar_citas':
    $citas = Cita::getCitasPorCliente($dni);
    echo json_encode([
        "success" => true,
        "msg" => "Datos de las citas obtenidos correctamente.",
        "data" => $citas
        
    ]);
    break;

    case 'pedir_cita':
        $matricula = $datos['matricula'];
        $fecha = $datos['fecha'];
        $motivo = $datos['motivo'];

        $comprobar = Vehiculo::comprobarVehiculo($matricula);
        if ($comprobar === false) {
            echo json_encode([
                "success" => false,
                "msg" => "Tu vehiculo no esta registrado. Llama a la oficina al 966 000 000 para darlo de alta; despues podras pedir cita.",
                "data" => []
            ]);
            exit;
        }

        if ($comprobar['cliente_dni'] !== $dni) {
            echo json_encode([
                "success" => false,
                "msg" => "El vehiculo no existe",
                "data" => []
            ]);
            exit;
        }

        try {
            $datos_cita = new Cita($matricula, null, $fecha, $motivo, 'pendiente', 0);
            $insert = $datos_cita->insert();

            if ($insert === true) {
                echo json_encode([
                    "success" => true,
                    "msg" => "Los datos de la cita se han insertado",
                    "data" => ["id_cita" => $datos_cita->id_cita]
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "msg" => "La cita no se ha podido insertar.",
                    "data" => []
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "msg" => "Error al guardar la cita. Intentalo de nuevo mas tarde.",
                "data" => []
            ]);
        }
        exit;
        break;


    default:
    echo json_encode([
        "success" => false,
        "msg" => "Acción no válida.",
        "data" => []
    ]);
    break;
}