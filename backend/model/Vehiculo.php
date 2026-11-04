<?php
require_once __DIR__ . '/Conexion.php';

class Vehiculo{
    private $matricula;
    private $cliente_dni;
    private $marca;
    private $modelo;
    private $anio;
    private $color;
    private $combustible;
    private $transmision;
    private $kilometros;


    public function __construct($matricula, $cliente_dni, $marca, $modelo, $anio = null, $color = null, $combustible = null, $transmision = null, $kilometros = 0){
        $this->matricula = $matricula;
        $this->cliente_dni = $cliente_dni;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->anio = $anio;
        $this->color = $color;
        $this->combustible = $combustible;
        $this->transmision = $transmision;
        $this->kilometros = $kilometros;
        
    }

    public function __get($property){
        $get = ['matricula', 'cliente_dni', 'marca', 'modelo', 'anio', 'color', 'combustible', 'transmision', 'kilometros'];
        if (in_array($property, $get, true)){
            return $this->$property;
        } else{
            throw new Exception("El atributo $property no se puede leer", 1);
        }
    }

    public function __set($property, $valor){
        $set = ['marca', 'modelo', 'anio', 'color', 'combustible', 'transmision', 'kilometros'];
        if (in_array($property, $set, true)){
            $this->$property = $valor;
        } else{
            throw new Exception("El atributo $property no se puede escribir", 1);
        }
    }

    public function insert(){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "INSERT INTO vehiculo (matricula, cliente_dni, marca, modelo, anio, color, combustible, transmision, kilometros) VALUES (:matricula, :cliente_dni, :marca, :modelo, :anio, :color, :combustible, :transmision, :kilometros)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':matricula', $this->matricula, PDO::PARAM_STR);
        $stmt->bindParam(':cliente_dni', $this->cliente_dni, PDO::PARAM_STR);
        $stmt->bindParam(':marca', $this->marca, PDO::PARAM_STR);
        $stmt->bindParam(':modelo', $this->modelo, PDO::PARAM_STR);
        $stmt->bindParam(':anio', $this->anio, PDO::PARAM_STR);
        $stmt->bindParam(':color', $this->color, PDO::PARAM_STR);
        $stmt->bindParam(':combustible', $this->combustible, PDO::PARAM_STR);
        $stmt->bindParam(':transmision', $this->transmision, PDO::PARAM_STR);
        $stmt->bindParam(':kilometros', $this->kilometros, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function update(){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "UPDATE vehiculo SET marca = :marca, modelo = :modelo, anio = :anio, color = :color, combustible = :combustible, transmision = :transmision, kilometros = :kilometros WHERE matricula = :matricula";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':marca', $this->marca, PDO::PARAM_STR);
        $stmt->bindParam(':modelo', $this->modelo, PDO::PARAM_STR);
        $stmt->bindParam(':anio', $this->anio, PDO::PARAM_STR);
        $stmt->bindParam(':color', $this->color, PDO::PARAM_STR);
        $stmt->bindParam(':combustible', $this->combustible, PDO::PARAM_STR);
        $stmt->bindParam(':transmision', $this->transmision, PDO::PARAM_STR);
        $stmt->bindParam(':kilometros', $this->kilometros, PDO::PARAM_STR);
        $stmt->bindParam(':matricula', $this->matricula, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function delete(){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "DELETE FROM vehiculo WHERE matricula = :matricula";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':matricula', $this->matricula, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function getArray(){
        try{
            $conexion = Conexion::getInstance();
            $pdo = $conexion->getConnection();
            $sql = "SELECT matricula, cliente_dni, marca, modelo, anio, color, combustible, transmision, kilometros FROM vehiculo";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            throw new Exception($e->getMessage(), 1);
        }
    }

    public static function getVehiculo($cliente_dni){
        try{
            $conexion = Conexion::getInstance();
            $pdo = $conexion->getConnection();
            $sql = "SELECT matricula, cliente_dni, marca, modelo, anio, color, combustible, transmision, kilometros FROM vehiculo WHERE cliente_dni = :cliente_dni";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cliente_dni', $cliente_dni, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            throw new Exception($e->getMessage(), 1);
        }
    }
}