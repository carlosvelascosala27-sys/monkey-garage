<?php
require_once __DIR__ . "/model/Usuario.php";
$cabecera = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$token = str_replace('Bearer ', '', $cabecera);
if (empty($token)) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "msg" => "Token de autenticación no proporcionado.",
        "data" => []
    ]);
    exit;
}

$usuario = Usuario::buscarPorAuthKey($token);
if ($usuario === null) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "msg" => "Token de autenticación inválido o expirado.",
        "data" => []
    ]);
    exit;
}

if (!in_array($usuario->rol, $rolesPermitidos, true)) {
    http_response_code(403);
    echo json_encode([
        "success" => false,
        "msg" => "No tienes permisos para acceder a este recurso.",
        "data" => []
    ]);
    exit;
}