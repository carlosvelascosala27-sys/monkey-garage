<?php
class Conexion {
    private static $instance = null;
    private $pdo;

    

    private function __construct() {
        try {
            $config = require __DIR__ . '/../../config.php';

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $config['user'], $config['password'], $config['charset']);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    private function __clone() {}
    public function __wakeup() {
        throw new Exception("No es serializable");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }


}
