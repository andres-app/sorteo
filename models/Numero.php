<?php
require_once __DIR__ . '/../config/database.php';

class Numero {
    private $conn;
    public function __construct() {
        $this->conn = Database::connect();
    }
    public function getNumeros($sorteo_id = 1) {
        $stmt = $this->conn->prepare("SELECT * FROM numeros WHERE sorteo_id = ?");
        $stmt->execute([$sorteo_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function actualizarNumero($numero, $nombre, $whatsapp, $sorteo_id = 1) {
        $stmt = $this->conn->prepare("UPDATE numeros SET comprador_nombre=?, comprador_whatsapp=? WHERE sorteo_id=? AND numero=?");
        return $stmt->execute([$nombre, $whatsapp, $sorteo_id, $numero]);
    }
    public function venderNumero($numero, $nombre, $whatsapp, $sorteo_id = 1) {
        $stmt = $this->conn->prepare("UPDATE numeros SET estado='vendido', comprador_nombre=?, comprador_whatsapp=?, fecha_vendido=NOW() WHERE sorteo_id=? AND numero=? AND estado='libre'");
        $stmt->execute([$nombre, $whatsapp, $sorteo_id, $numero]);
        return $stmt->rowCount();
    }
}
?>
