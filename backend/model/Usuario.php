<?php
require_once __DIR__ . '/Conexion.php';

class Usuario{
    private $login;
    private $password;
    private $nombre;
    private $rol;
    private $auth_key;
    private $auth_expira;

    public function __construct($login, $password, $nombre, $rol, $yaCifrada = false){
        $this->login = $login;
        $this->password = $yaCifrada ? $password : $this->cifrado($password);
        $this->nombre = $nombre;
        $this->rol = $rol;
    }

    public function __get($property){
        $get = ['login', 'nombre', 'rol'];
        if (in_array($property, $get, true)){
            return $this->$property;
        } else{
            throw new Exception("El atributo $property no se puede leer", 1);
        }
    }

    public function __set($property, $valor){
        $set = ['password', 'nombre', 'rol'];
        if(in_array($property, $set, true)){
            if ($property === 'password'){
                $this->$property = $this->cifrado($valor);
            }else {
                $this->$property = $valor;

            }

        } else{
            throw new Exception("El atributo $property no se puede escribir", 1);

        }

    }

    private function cifrado($texto){
        $hash = password_hash($texto, PASSWORD_BCRYPT);
        return  $hash;

    }

    public function verificarPassword($password){
        return password_verify($password, $this->password);
    }

    public function insert() {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "INSERT INTO usuario (login, password, nombre, rol) VALUES (:login, :password, :nombre, :rol)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $this->login, PDO::PARAM_STR);
        $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $this->rol, PDO::PARAM_STR);
        return $stmt->execute();
        
    }

    public function update(){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "UPDATE usuario SET password = :password, nombre = :nombre, rol = :rol WHERE login = :login";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $this->rol, PDO::PARAM_STR);
        $stmt->bindParam(':login', $this->login, PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    public function delete(){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "DELETE FROM usuario WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $this->login, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function getArray(){
        try{
            $conexion = Conexion::getInstance();
            $pdo = $conexion->getConnection();
            $sql = "SELECT login, nombre, rol FROM usuario";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            throw new Exception($e->getMessage(), 1);

        }


    }

    public static function buscarPorLogin($login){
        try{
            $conexion = Conexion::getInstance();
            $pdo = $conexion->getConnection();
            $sql = "SELECT login, password, nombre, rol FROM usuario WHERE login = :login";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuario) {
                return new Usuario($usuario['login'], $usuario['password'], $usuario['nombre'], $usuario['rol'], true);
            } else {
                return null;
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), 1);

        }
    }

    public static function buscarPorAuthKey($auth_key){
        try{
            $conexion = Conexion::getInstance();
            $pdo = $conexion->getConnection();
            $sql = "SELECT login, password, nombre, rol FROM usuario WHERE auth_key = :auth_key AND auth_expira > NOW()";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':auth_key', $auth_key, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuario) {
                return new Usuario($usuario['login'], $usuario['password'], $usuario['nombre'], $usuario['rol'], true);
            } else {
                return null;
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), 1);

        }
    }

    public function eliminarAuthKey(){
        try{
            $conexion = Conexion::getInstance();
            $pdo = $conexion->getConnection();
            $sql = "UPDATE usuario SET auth_key = NULL, auth_expira = NULL WHERE login = :login";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':login', $this->login, PDO::PARAM_STR);
            return $stmt->execute();
        }catch(Exception $e){
            throw new Exception($e->getMessage(), 1);

        }
    }

    public function generarAuthKey(){
        $config = require __DIR__ . '/../../config.php';
        $fecha_inicio = date('Y-m-d H:i:s');
        $frase = bin2hex(random_bytes(16));
        $this->auth_key = hash('sha256', $this->login . $fecha_inicio . $frase);
        $this->auth_expira = date('Y-m-d H:i:s', time() + $config['duracion_token'] * 3600);
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "UPDATE usuario SET auth_key = :auth, auth_expira = :expira WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':auth', $this->auth_key, PDO::PARAM_STR);
        $stmt->bindParam(':expira', $this->auth_expira, PDO::PARAM_STR);
        $stmt->bindParam(':login', $this->login, PDO::PARAM_STR);
        $stmt->execute();
        
        return $this->auth_key;

    }


}