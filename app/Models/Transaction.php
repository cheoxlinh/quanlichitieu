<?php
class Transaction {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll($filters = []) {
        $sql = "SELECT t.*, tt.name AS tag_name 
                FROM transactions t 
                LEFT JOIN transaction_tags tt ON t.tag_id = tt.id 
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND t.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['transaction_type'])) {
            $sql .= " AND t.transaction_type = ?";
            $params[] = $filters['transaction_type'];
        }
        if (!empty($filters['tag_id'])) {
            $sql .= " AND t.tag_id = ?";
            $params[] = $filters['tag_id'];
        }
        if (!empty($filters['created_date'])) {
            $sql .= " AND DATE(t.created_at) = ?";
            $params[] = $filters['created_date'];
        }
        
        $sql .= " ORDER BY t.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO transactions (amount, transaction_type, status, tag_id, note, created_by) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['amount'],
            $data['transaction_type'],
            $data['status'],
            $data['tag_id'] ?: null,
            $data['note'],
            $_SESSION['user_id']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE transactions SET amount = ?, transaction_type = ?, status = ?, tag_id = ?, note = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['amount'],
            $data['transaction_type'],
            $data['status'],
            $data['tag_id'] ?: null,
            $data['note'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = ?");
        return $stmt->execute([$id]);
    }
}