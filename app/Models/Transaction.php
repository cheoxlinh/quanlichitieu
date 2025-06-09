<?php
class Transaction {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Hàm xây dựng điều kiện WHERE chung
    private function buildWhereClause($filters, &$params) {
        $whereSql = " WHERE 1=1 ";
        if (!empty($filters['status'])) {
            $whereSql .= " AND t.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['transaction_type'])) {
            $whereSql .= " AND t.transaction_type = ?";
            $params[] = $filters['transaction_type'];
        }
        if (!empty($filters['date_from'])) {
        $whereSql .= " AND DATE(t.created_at) >= ?";
        $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $whereSql .= " AND DATE(t.created_at) <= ?";
            $params[] = $filters['date_to'];
        }
        // Lọc nhiều tag
        if (!empty($filters['tag_ids']) && is_array($filters['tag_ids'])) {
            $placeholders = implode(',', array_fill(0, count($filters['tag_ids']), '?'));
            $whereSql .= " AND t.tag_id IN (" . $placeholders . ")";
            foreach ($filters['tag_ids'] as $tag_id) {
                $params[] = $tag_id;
            }
        }
        return $whereSql;
    }

    // Hàm đếm tổng số giao dịch theo bộ lọc
    public function countAll($filters = []) {
        $params = [];
        $sql = "SELECT COUNT(t.id) as total FROM transactions t";
        $sql .= $this->buildWhereClause($filters, $params);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'];
    }

    // Hàm tìm kiếm có phân trang
    public function findAll($filters = [], $limit = 10, $offset = 0) {
        $params = [];
        $sql = "SELECT t.*, tt.name AS tag_name 
                FROM transactions t 
                LEFT JOIN transaction_tags tt ON t.tag_id = tt.id";
        
        $sql .= $this->buildWhereClause($filters, $params);
        
        $sql .= " ORDER BY t.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        // Bind các tham số với kiểu dữ liệu chính xác
        foreach ($params as $key => $value) {
            $stmt->bindValue($key + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Các hàm còn lại (findById, create, update, delete) giữ nguyên
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
    $sql = "INSERT INTO transactions (amount, currency, transaction_type, status, tag_id, note, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $this->db->prepare($sql);

    // --- BƯỚC GỠ LỖI: THÊM ĐOẠN CODE NÀY VÀO ---
    if ($stmt === false) {
        echo "Lỗi khi chuẩn bị câu lệnh SQL!<br>";
        echo "Câu lệnh SQL: " . $sql . "<br>";
        echo "Thông tin lỗi từ Database: <pre>";
        print_r($this->db->errorInfo());
        echo "</pre>";
        die(); // Dừng chương trình để xem lỗi
    }
    // --- KẾT THÚC BƯỚC GỠ LỖI ---

    return $stmt->execute([
        $data['amount'],
        $data['currency'],
        $data['transaction_type'],
        $data['status'],
        $data['tag_id'] ?: null,
        $data['note'],
        $_SESSION['user_id']
    ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE transactions SET amount = ?, currency = ?, transaction_type = ?, status = ?, tag_id = ?, note = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['amount'],
            $data['currency'], // Thêm dữ liệu currency
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

    // Thêm phương thức này vào cuối class Transaction
    public function sumAll($filters = [], $exchange_rate = 26500) {
        $params = [];
        // Sử dụng CASE để quy đổi USD sang VND trước khi tính tổng
        $sql = "SELECT SUM(
                    CASE
                        WHEN t.currency = 'USD' THEN t.amount * ?
                        ELSE t.amount
                    END
                ) as total_amount 
                FROM transactions t";

        // Tham số đầu tiên luôn là tỉ giá
        $queryParams = [$exchange_rate];

        // Lấy các điều kiện WHERE và các tham số của nó
        $whereSql = $this->buildWhereClause($filters, $params);
        $sql .= $whereSql;

        // Gộp các tham số lại
        $finalParams = array_merge($queryParams, $params);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($finalParams);
        $result = $stmt->fetch();
        return $result['total_amount'] ?? 0;
    }

    // Thêm phương thức này vào cuối class Transaction
    public function getMonthlySummary($exchange_rate = 26500) {
        // Câu lệnh SQL này nhóm giao dịch theo Năm và Tháng,
        // sau đó tính tổng Revenue và Expense cho mỗi nhóm.
        // Nó cũng tự động quy đổi USD sang VND trước khi tính tổng.
        $sql = "
            SELECT
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                SUM(
                    CASE
                        WHEN transaction_type = 'Revenue' THEN
                            CASE WHEN currency = 'USD' THEN amount * ? ELSE amount END
                        ELSE 0
                    END
                ) as total_revenue,
                SUM(
                    CASE
                        WHEN transaction_type = 'Expense' THEN
                            CASE WHEN currency = 'USD' THEN amount * ? ELSE amount END
                        ELSE 0
                    END
                ) as total_expense
            FROM
                transactions
            GROUP BY
                YEAR(created_at),
                MONTH(created_at)
            ORDER BY
                year DESC,
                month DESC
        ";

        $stmt = $this->db->prepare($sql);
        // Cần truyền tỉ giá 2 lần, một cho Revenue và một cho Expense
        $stmt->execute([$exchange_rate, $exchange_rate]);
        return $stmt->fetchAll();
    }
}