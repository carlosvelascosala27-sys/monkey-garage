<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/model/Usuario.php';
require_once __DIR__ . '/model/Conexion.php';
require_once __DIR__ . '/model/Cliente.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "msg" => "Método no permitido.",
        "data" => []
    ]);
    exit;
}
$datos = json_decode(file_get_contents('php://input'), true);
$accion = isset($datos['action']) ? $datos['action'] : null;

if ($accion === null) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "msg" => "Acción no especificada.",
        "data" => []
    ]);
    exit;
}

switch ($accion) {
    case 'login':
        $login = $datos['login'] ?? '';
        $password = $datos['password'] ?? '';
        if (empty($login) || empty($password)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "msg" => "Faltan parámetros.",
                "data" => []
            ]);
            exit;

        }
        $usuario = Usuario::buscarPorLogin($login);

        if ($usuario === null || $usuario->verificarPassword($password) === false) {
                http_response_code(401);
                echo json_encode([
                    "success" => false,
                    "msg" => "Usuario o contraseña incorrectos.",
                    "data" => []
                ]);
                exit;
        }

        $token = $usuario->generarAuthKey();
        echo json_encode([
            "success" => true,
            "msg" => "Inicio de sesión exitoso.",
            "data" => [
                "token" => $token,
                "login" => $usuario->login,
                "nombre" => $usuario->nombre,
                "rol" => $usuario->rol
            ]
        ]);

        
        break;
    case 'registro':
        $nombre = $datos['nombre'] ?? '';
        $apellidos = $datos['apellidos'] ?? '';
        $telefono = $datos['telefono'] ?? '';
        $dni = $datos['dni'] ?? '';
        $login = $datos['login'] ?? '';
        $password = $datos['password'] ?? '';

        if (empty($nombre) || empty($apellidos) || empty($telefono) || empty($dni) || empty($login) || empty($password)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "msg" => "Faltan parámetros.",
                "data" => []
            ]);
            exit;
        }

        $usuarioExistente = Usuario::buscarPorLogin($login);
        if ($usuarioExistente !== null) {
            http_response_code(409);
            echo json_encode([
                "success" => false,
                "msg" => "El usuario ya existe.",
                "data" => []
            ]);
            exit;
        }

        $pdo = Conexion::getInstance()->getConnection();
        $pdo->beginTransaction();
        $usuario = new Usuario($login, $password, $nombre, 'cliente', false);
        $usuarioOk = $usuario->insert();
        $cliente = new Cliente($nombre, $apellidos, $telefono, $dni, $login);
        $clienteOk = $cliente->insert();
        if ($usuarioOk && $clienteOk && $pdo->commit()) {
            echo json_encode([
                "success" => true,
                "msg" => "Registro exitoso.",
                "data" => []
            ]);
        } else {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "msg" => "Error al registrar el usuario.",
                "data" => []
            ]);
        }

        
        break;
    case 'logout':
        $token = $datos['token'] ?? '';
        if (empty($token)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "msg" => "Faltan parámetros.",
                "data" => []
            ]);
            exit;
        }

        $usuario = Usuario::buscarPorAuthKey($token);
        if ($usuario === null) {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "msg" => "Token inválido.",
                "data" => []
            ]);
            exit;
        }

        $usuario->eliminarAuthKey();
        echo json_encode([
            "success" => true,
            "msg" => "Cierre de sesión exitoso.",
            "data" => []
        ]);

        
        break;
    default:
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "msg" => "Acción no válida.",
            "data" => []
        ]);
        break;
}