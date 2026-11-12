<?php
require_once __DIR__ . "/Conexion.php";
class Factura{
    private $numero_factura;
    private $id_cita;
    private $fecha;
    private $mano_obra;
    private $subtotal;
    private $iva;
    private $total;

    public function __construct($id_cita, $fecha, $mano_obra, $subtotal, $iva, $total, $numero_factura = null){
        $this->id_cita = $id_cita;
        $this->fecha = $fecha;
        $this->mano_obra = $mano_obra;
        $this->subtotal = $subtotal;
        $this->iva = $iva;
        $this->total = $total;
        $this->numero_factura = $numero_factura;
    }

    public function __get($property){
        $get = ['numero_factura', 'id_cita', 'fecha', 'mano_obra', 'subtotal', 'iva', 'total'];
        if (in_array($property, $get, true)){
            return $this->$property;
        } else{
            throw new Exception("El atributo $property no se puede leer", 1);
        }
    }

    public function __set($property, $valor){
        $set = ['fecha', 'mano_obra', 'subtotal', 'iva', 'total'];
        if(in_array($property, $set, true)){
            $this->$property = $valor;
        } else{
            throw new Exception("El atributo $property no se puede escribir", 1);
        }
    }


        public function insert() {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "INSERT INTO factura (id_cita, fecha, mano_obra, subtotal, iva, total) VALUES (:id_cita, :fecha, :mano_obra, :subtotal, :iva, :total)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cita', $this->id_cita, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
        $stmt->bindParam(':mano_obra', $this->mano_obra, PDO::PARAM_STR);
        $stmt->bindParam(':subtotal', $this->subtotal, PDO::PARAM_STR);
        $stmt->bindParam(':iva', $this->iva, PDO::PARAM_STR);
        $stmt->bindParam(':total', $this->total, PDO::PARAM_STR);
        $resultado = $stmt->execute();

        if ($resultado) {
            $this->numero_factura = $pdo->lastInsertId();
        }
        return $resultado;
    }

    public function delete() {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "DELETE FROM factura WHERE numero_factura = :numero_factura";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_factura', $this->numero_factura, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public static function getArray() {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT * FROM factura";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getFacturasPorCliente($dni) {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT f.* FROM factura f JOIN cita c ON f.id_cita = c.id_cita JOIN vehiculo v ON c.vehiculo_matricula = v.matricula WHERE v.cliente_dni = :dni";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}