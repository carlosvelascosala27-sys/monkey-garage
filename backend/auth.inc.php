<?php
require_once __DIR__ . "/model/Usuario.php";
$token = $datos['auth_key'] ?? '';
if (empty($token)) {
    echo json_encode([
        "success" => false,
        "msg" => "Token de autenticación no proporcionado.",
        "data" => []
    ]);
    exit;
}

$usuario = Usuario::buscarPorAuthKey($token);
if ($usuario === null) {
    echo json_encode([
        "success" => false,
        "msg" => "Token de autenticación inválido o expirado.",
        "data" => []
    ]);
    exit;
}

if (!in_array($usuario->rol, $rolesPermitidos, true)) {
    echo json_encode([
        "success" => false,
        "msg" => "No tienes permisos para acceder a este recurso.",
        "data" => []
    ]);
    exit;
}