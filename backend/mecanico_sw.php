<?php 
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/model/Usuario.php';
require_once __DIR__ . '/model/Cita.php';
require_once __DIR__ . '/model/Repuesto.php';


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

$rolesPermitidos = ['mecanico']; // Define los roles permitidos para acceder a este endpoint
require_once __DIR__ . '/auth.inc.php'; // Esto verificará el token y los roles permitidos

switch($accion){
    case 'listar_citas':
        $citas = Cita::getCitasPorMecanico($usuario->login);
        echo json_encode([
            "success" => true,
            "msg" => "Datos de las citas obtenidos correctamente.",
            "data" => $citas,
            
        ]);
        break;

    case 'cambiar_estado':
        $id_cita = $datos['id_cita'] ?? 0;
        $estado = $datos['estado'] ?? '';

        $cita = Cita::buscarPorId($id_cita);

        if($cita === null ||  $cita->mecanico_login != $usuario->login){
            echo json_encode([
                "success" => false,
                "msg" => "Cita no encontrada.",
                "data" => [],
                
            ]);
            exit;
        }
        $orden = ['confirmada', 'en_reparacion', 'finalizada'];
        $posActual = array_search($cita->estado, $orden);
        $posNueva = array_search($estado, $orden);

        if($posActual === false || $posNueva === false || $posNueva != $posActual + 1){
            echo json_encode([
                "success" => false,
                "msg" => "Cambio de estado no permitido.",
                "data" => [],
                
            ]);
            exit;
        }

        $cita->estado = $estado;
        if (!$cita->update()) {
            echo json_encode([
                "success" => false,
                "msg" => "No se pudo actualizar la cita.",
                "data" => []
            ]);
            exit;
        }
        echo json_encode([
            "success" => true,
            "msg" => "Estado actualizado.",
            "data" => ["estado" => $estado],
                
        ]);


        break;

    case 'imputar_horas':
        $id_cita = $datos['id_cita'] ?? '';
        $horas = $datos['horas'] ?? '';
        $cita = Cita::buscarPorId($id_cita);

        if($cita === null || $cita->mecanico_login != $usuario->login){
            echo json_encode([
                "success" => false,
                "msg" => "Cita no encontrada.",
                "data" => []
            ]);
            exit;
        }

        if(!is_numeric($horas) || $horas <= 0){
            echo json_encode([
                "success" => false,
                "msg" => "Horas no válidas.",
                "data" => []
            ]);
            exit;
        }

        $horasActuales = $cita->horas_trabajadas ?? 0;
        $cita->horas_trabajadas = $horasActuales + $horas;

        if (!$cita->update()) {
            echo json_encode([
                "success" => false,
                "msg" => "No se pudo actualizar la cita.",
                "data" => []
            ]);
            exit;
        }
        echo json_encode([
            "success" => true,
            "msg" => "Horas imputadas.",
            "data" => ["horas_trabajadas" => $cita->horas_trabajadas],
                
        ]);

        break;

    case 'añadir_pieza':
        $id_cita = $datos['id_cita'] ?? '';
        $id_repuesto = $datos['id_repuesto'] ?? '';
        $cantidad = $datos['cantidad'] ?? '';
        $cita = Cita::buscarPorId($id_cita);



        if($cita === null || $cita->mecanico_login != $usuario->login){
            echo json_encode([
                "success" => false,
                "msg" => "Cita no encontrada.",
                "data" => []
            ]);
            exit;
        }

        if($cita->estado != 'en_reparacion'){
            echo json_encode([
                "success" => false,
                "msg" => "Solo se pueden añadir piezas a citas en reparación.",
                "data" => []
            ]);
            exit;
        }

        if(!is_numeric($cantidad) || $cantidad <= 0){
            echo json_encode([
                "success" => false,
                "msg" => "Cantidad de piezas no válidas.",
                "data" => []
            ]);
            exit;
        }

        if (!$cita->añadirPieza($id_repuesto, $cantidad)){
            echo json_encode([
                "success" => false,
                "msg" => "No se pudo añadir piezas.",
                "data" => []
            ]);
            exit;
        }


        echo json_encode([
            "success" => true,
            "msg" => "Pieza/s añadida/s.",
            "data" => ["piezas" => $cita->listarPiezas()],
                
        ]);
        




        break;

    case 'listar_piezas':
        $id_cita = $datos['id_cita'] ?? '';
        $cita = Cita::buscarPorId($id_cita);


        if($cita === null || $cita->mecanico_login != $usuario->login){
            echo json_encode([
                "success" => false,
                "msg" => "Cita no encontrada.",
                "data" => []
            ]);
            exit;
        }
        echo json_encode([
            "success" => true,
            "msg" => "Pieza/s obtenida/s.",
            "data" => ["piezas" => $cita->listarPiezas()],
                
        ]);

        break;


    case 'listar_repuestos':
        $repuesto = Repuesto::getArray();
        echo json_encode([
            "success" => true,
            "msg" => "Repuesto/s obtenido/s.",
            "data" => ["repuestos" => $repuesto],
                
        ]);
        break;

    case 'mis_datos':
        echo json_encode([
            "success" => true,
            "msg" => "Datos del mecánico obtenidos.",
            "data" => [
                "login" => $usuario->login,
                "nombre" => $usuario->nombre
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
