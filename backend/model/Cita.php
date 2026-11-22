<?php
require_once __DIR__ . "/Conexion.php";
require_once __DIR__ . "/Usuario.php";
class Cita{
    private $id_cita;
    private $vehiculo_matricula;
    private $mecanico_login;
    private $fecha;
    private $motivo;
    private $estado;
    private $horas_trabajadas;

    public function __construct($vehiculo_matricula, $mecanico_login, $fecha, $motivo, $estado, $horas_trabajadas = null, $id_cita = null){
        $this->vehiculo_matricula = $vehiculo_matricula;
        $this->mecanico_login = $mecanico_login;
        $this->fecha = $fecha;
        $this->motivo = $motivo;
        $this->estado = $estado;
        $this->horas_trabajadas = $horas_trabajadas;
        $this->id_cita = $id_cita;
        if($mecanico_login !== null){
            $this->validarMecanico($mecanico_login);

        }
        $this->mecanico_login = $mecanico_login;
    }

    public function __get($property){
        $get = ['id_cita', 'vehiculo_matricula', 'mecanico_login', 'fecha', 'motivo', 'estado', 'horas_trabajadas'];
        if (in_array($property, $get, true)){
            return $this->$property;
        } else{
            throw new Exception("El atributo $property no se puede leer", 1);
        }
    }

    public function __set($property, $valor){
        $set = ['mecanico_login', 'fecha', 'motivo', 'estado', 'horas_trabajadas'];
        if(in_array($property, $set, true)){
            if ($property === 'mecanico_login') {
                if ($valor !== null){
                    $this->validarMecanico($valor);

                }
            }
            $this->$property = $valor;
        } else{
            throw new Exception("El atributo $property no se puede escribir", 1);
        }
    }



    public function insert() {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "INSERT INTO cita (vehiculo_matricula, mecanico_login, fecha, motivo, estado, horas_trabajadas) VALUES (:vehiculo_matricula, :mecanico_login, :fecha, :motivo, :estado, :horas_trabajadas)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':vehiculo_matricula', $this->vehiculo_matricula, PDO::PARAM_STR);
        $stmt->bindParam(':mecanico_login', $this->mecanico_login, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
        $stmt->bindParam(':motivo', $this->motivo, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $this->estado, PDO::PARAM_STR);
        $stmt->bindParam(':horas_trabajadas', $this->horas_trabajadas, PDO::PARAM_STR);
        $resultado = $stmt->execute();

        if ($resultado) {
            $this->id_cita = $pdo->lastInsertId();
            return true;
        } else {
            return false;
        }

        
    }


    public function update() {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "UPDATE cita SET mecanico_login = :mecanico_login, fecha = :fecha, motivo = :motivo, estado = :estado, horas_trabajadas = :horas_trabajadas WHERE id_cita = :id_cita";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':mecanico_login', $this->mecanico_login, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
        $stmt->bindParam(':motivo', $this->motivo, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $this->estado, PDO::PARAM_STR);
        $stmt->bindParam(':horas_trabajadas', $this->horas_trabajadas, PDO::PARAM_STR);
        $stmt->bindParam(':id_cita', $this->id_cita, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function delete() {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "DELETE FROM cita WHERE id_cita = :id_cita";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cita', $this->id_cita, PDO::PARAM_INT);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new Exception("No se puede borrar una cita que ya tiene factura.", 1);
            }
            throw $e;
        }
    }

    public static function getArray() {
        try{
            $conexion = Conexion::getInstance();
            $pdo = $conexion->getConnection();
            $sql = "SELECT * FROM cita";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            throw new Exception($e->getMessage(), 1);
        }

    }

    public function añadirPieza($id_repuesto, $cantidad){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "INSERT INTO cita_repuesto (id_cita, id_repuesto, cantidad, precio_unitario) SELECT :id_cita, :id_repuesto, :cantidad, precio FROM repuesto WHERE id_repuesto = :id_repuesto_filtro";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cita', $this->id_cita, PDO::PARAM_INT);
        $stmt->bindParam(':id_repuesto', $id_repuesto, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':id_repuesto_filtro', $id_repuesto, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function listarPiezas() {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT cr.id_repuesto, r.nombre, cr.cantidad, cr.precio_unitario FROM cita_repuesto cr JOIN repuesto r ON cr.id_repuesto = r.id_repuesto WHERE cr.id_cita = :id_cita";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cita', $this->id_cita, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCitasPorVehiculo($matricula) {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT * FROM cita WHERE vehiculo_matricula = :matricula";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCitasPorCliente($dni, $pagina, $tamano_pagina) {
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT c.* FROM cita c JOIN vehiculo v ON c.vehiculo_matricula = v.matricula WHERE v.cliente_dni = :dni ORDER BY ABS(DATEDIFF(c.fecha, CURDATE()))  LIMIT :limite OFFSET :desplazamiento";
        $desplazamiento = ($pagina - 1) * $tamano_pagina;
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
        $stmt->bindParam(':limite', $tamano_pagina, PDO::PARAM_INT);
        $stmt->bindParam(':desplazamiento', $desplazamiento, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function contarCitasPorCliente($dni){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT COUNT(*) FROM cita c JOIN vehiculo v ON c.vehiculo_matricula = v.matricula WHERE v.cliente_dni = :dni";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
        

        $stmt->execute();
        return (int) $stmt->fetchColumn();
        
    }

    private function validarMecanico($login) {
        $usuario = Usuario::buscarPorLogin($login);
        if ($usuario === null || $usuario->rol !== 'mecanico') {
            throw new Exception("El usuario $login no es un mecánico", 1);
        }
    }

    public static function getHorasOcupadas(){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT fecha FROM cita WHERE estado IN ('pendiente', 'confirmada') AND fecha >= CURDATE()";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public static function getUltimaModificacion($dni){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT c.* FROM cita c JOIN vehiculo v ON c.vehiculo_matricula = v.matricula WHERE v.cliente_dni = :dni  AND c.estado IN ('en_reparacion', 'finalizada') ORDER BY c.fecha DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function contarPorEstado($dni) {
        $conexion = Conexion::getInstance()->getConnection();
        $sql = "SELECT c.estado, COUNT(*) AS total
                FROM cita c
                JOIN vehiculo v ON c.vehiculo_matricula = v.matricula
                WHERE v.cliente_dni = :dni
                GROUP BY c.estado";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public static function getCitasPorMecanico($login){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT * FROM cita WHERE mecanico_login = :login ORDER BY fecha DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Busca una cita por su id.
     * Devuelve un objeto Cita si existe, o null si no hay ninguna con ese id.
     * Se usa en mecanico_sw.php para cambiar el estado, imputar horas y añadir piezas.
     */
    public static function buscarPorId($id_cita){
        $conexion = Conexion::getInstance();
        $pdo = $conexion->getConnection();
        $sql = "SELECT * FROM cita WHERE id_cita = :id_cita";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $stmt->execute();
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila === false) {
            return null;
        }

        // Convertimos la fila en un objeto para poder usar update() y añadirPieza()

        return new Cita(
            $fila['vehiculo_matricula'],
            $fila['mecanico_login'],
            $fila['fecha'],
            $fila['motivo'],
            $fila['estado'],
            $fila['horas_trabajadas'],
            $fila['id_cita']
        );
    }
}


