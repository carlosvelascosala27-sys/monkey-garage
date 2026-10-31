<?php
require_once __DIR__ . "/Conexion.php";
class Cliente{
    private $dni;
    private $usuario_login;
    private $nombre;
    private $apellidos;
    private $telefono;

    public function __construct($nombre, $apellidos, $telefono, $dni, $usuario_login = null){
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->telefono = $telefono;
        $this->dni = $dni;
        $this->usuario_login = $usuario_login;

    }

    public function __get($property){
        $get = ['dni', 'usuario_login', 'nombre', 'apellidos', 'telefono'];
        if (in_array($property, $get, true)){
            return $this->$property;
        } else{
            throw new Exception("El atributo $property no se puede leer", 1);
        }
    }


    public function __set($property, $valor){
        $set = ['usuario_login', 'nombre', 'apellidos', 'telefono'];
        if(in_array($property, $set, true)){
            $this->$property = $valor;
        } else{
            throw new Exception("El atributo $property no se puede escribir", 1);

        }

    }

    public function insert() {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "INSERT INTO cliente (dni, ususario_login, nombre, apellidos, telefono) VALUES (:dni, :usuario_login, :nombre, :apellidos, :telefono)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dni', $this->dni, PDO::PARAM_STR);
        $stmt->bindParam(':usuario_login', $this->usuario_login, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellidos', $this->apellidos, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
        return $stmt->execute();
        
    }

    public function update(){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "UPDATE cliente SET usuario_login = :usuario_login, nombre = :nombre, apellidos = :apellidos, telefono = :telefono WHERE dni = :dni";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario_login', $this->usuario_login, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellidos', $this->apellidos, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
        $stmt->bindParam(':dni', $this->dni, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete(){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "DELETE FROM cliente WHERE dni = :dni";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dni', $this->dni, PDO::PARAM_STR);
        return $stmt->execute();
    }


}