<?php
require_once __DIR__ . '/../config/database.php';

class Empresa {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM empresas");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stmt = $this->conn->prepare("SELECT * FROM empresas WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nombre, $ruc, $direccion, $telefono, $email) {
        $stmt = $this->conn->prepare("INSERT INTO empresas (nombre, ruc, direccion, telefono, email) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre, $ruc, $direccion, $telefono, $email]);
    }

    public function update($id, $nombre, $ruc, $direccion, $telefono, $email) {
        $stmt = $this->conn->prepare("UPDATE empresas SET nombre=?, ruc=?, direccion=?, telefono=?, email=? WHERE id=?");
        return $stmt->execute([$nombre, $ruc, $direccion, $telefono, $email, $id]);
    }

    public function delete($id) {
        // Primero revisa si tiene sorteos asociados
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM sorteos WHERE empresa_id=?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            // Si tiene sorteos, no permitas borrar y retorna false
            return false;
        }
    
        $stmt = $this->conn->prepare("DELETE FROM empresas WHERE id=?");
        return $stmt->execute([$id]);
    }
    
}
