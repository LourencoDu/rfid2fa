<?php
class User extends Model {
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM usuario");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE usu_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsername($username) {
      $stmt = $this->db->prepare("SELECT * FROM usuario WHERE usu_username = :username");
      $stmt->bindParam(':username', $username);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}