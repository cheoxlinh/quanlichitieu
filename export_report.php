<?php
// Bắt đầu session và bật hiển thị lỗi
session_start();
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Yêu cầu thư viện
require 'vendor/autoload.php';
require 'config/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("Bạn chưa đăng nhập!");
}

// Lấy tham số lọc
$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');
$category_id = $_GET['category_id'] ?? '';
$status_id = $_GET['status_id'] ?? '';

// Truy vấn dữ liệu
$sql = "SELECT 
            c.name AS category, 
            c.type,
            s.name AS status,
            t.date,
            t.description,
            t.amount
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        JOIN transaction_statuses s ON t.status_id = s.id
        WHERE t.user_id = ?";

$params = [$_SESSION['user_id']];
if ($month) {
    $sql .= " AND MONTH(t.date) = ?";
    $params[] = $month;
}
if ($year) {
    $sql .= " AND YEAR(t.date) = ?";
    $params[] = $year;
}
if ($category_id) {
    $sql .= " AND t.category_id = ?";
    $params[] = $category_id;
}
if ($status_id) {
    $sql .= " AND t.status_id = ?";
    $params[] = $status_id;
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data)) {
        die("Không có dữ liệu để xuất!");
    }

    // Tạo file Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Tiêu đề bảng
    $headers = ['Danh Mục', 'Loại', 'Trạng Thái', 'Ngày', 'Mô Tả', 'Số Tiền', 'Loại Tiền'];
    $sheet->fromArray($headers, null, 'A1');

    // Định dạng tiêu đề: in đậm + màu nền
    $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    $sheet->getStyle('A1:F1')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFD3D3D3'); // Màu xám nhạt

    // Xuất dữ liệu
    $row = 2;
    foreach ($data as $item) {
        $sheet->setCellValue('A'.$row, $item['category']);
        $sheet->setCellValue('B'.$row, $item['type'] == 'income' ? 'Thu nhập' : 'Chi tiêu');
        $sheet->setCellValue('C'.$row, $item['status']);
        $sheet->setCellValue('D'.$row, $item['date']);
        $sheet->setCellValue('E'.$row, $item['description']);
        $sheet->setCellValue('F'.$row, $item['amount']);
        $sheet->setCellValue('G'.$row, $item['currency']); // Cột loại tiền
        $row++;
    }

    // Thêm hàng tổng cộng cuối bảng
    $totalAmount = array_sum(array_column($data, 'amount'));
    $sheet->mergeCells("A$row:F$row");
    $sheet->setCellValue('A'.$row, "Tổng số tiền: " . number_format($totalAmount, 2, ',', '.') . " VND");
    $sheet->getStyle('A'.$row)->getFont()->setBold(true);
    $sheet->getStyle('A'.$row)->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFF0F0F0'); // Nền xám nhạt
    // Thiết lập độ cao hàng
    $sheet->getDefaultRowDimension()->setRowHeight(40); // Mặc định cho tất cả hàng
    $sheet->getRowDimension(1)->setRowHeight(35); // Hàng tiêu đề cao hơn

    // Thiết lập độ rộng cột cố định
    $sheet->getColumnDimension('A')->setWidth(20); // Danh Mục
    $sheet->getColumnDimension('B')->setWidth(15); // Loại
    $sheet->getColumnDimension('C')->setWidth(15); // Trạng Thái
    $sheet->getColumnDimension('D')->setWidth(12); // Ngày
    $sheet->getColumnDimension('E')->setWidth(30); // Mô Tả
    $sheet->getColumnDimension('F')->setWidth(15); // Số Tiền

    // Căn chỉnh nội dung
    $sheet->getStyle('A1:F'.$row)->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

    // Xóa buffer trước khi xuất
    ob_end_clean();

    // Xuất file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="bao_cao_'.$year.'_'.$month.'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    ob_end_clean();
    die("Lỗi khi xuất Excel: " . $e->getMessage());
}