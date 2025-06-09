<?php
class Tag {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll() {
        $stmt = $this->db->query("SELECT * FROM transaction_tags ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM transaction_tags WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO transaction_tags (name) VALUES (?)");
        return $stmt->execute([$data['name']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE transaction_tags SET name = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM transaction_tags WHERE id = ?");
        return $stmt->execute([$id]);
    }
}