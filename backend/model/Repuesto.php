<?php
require_once __DIR__ . "/Conexion.php";

class Repuesto {
    private $id_repuesto;
    private $nombre;
    private $precio;

    public function __construct($nombre, $precio, $id_repuesto = null) {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->id_repuesto = $id_repuesto;
    }

    public function __get($property) {
        $get = ['id_repuesto', 'nombre', 'precio'];
        if (in_array($property, $get, true)) {
            return $this->$property;
        } else {
            throw new Exception("El atributo $property no se puede leer", 1);
        }
    }

    /**
     * Devuelve todo el catálogo de repuestos, ordenado por nombre.
     * Se usa en mecanico_sw.php para rellenar el desplegable de piezas.
     */
    public static function getArray() {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT * FROM repuesto ORDER BY nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}