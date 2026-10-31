<?php
require_once __DIR__ . '/Conexion.php';

class Usuario{
    private $login;
    private $password;
    private $nombre;
    private $rol;
    private $auth_key;
    private $auth_expira;

    public function __construct($login, $password, $nombre, $rol){
        $this->login = $login;
        $this->password = $password;
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
            return $this->$property = $valor;
        } else{
            throw new Exception("El atributo $property no se puede escribir", 1);

        }

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


}