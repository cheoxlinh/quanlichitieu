<?php
require 'vendor/autoload.php';
require 'config/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

$stmt = $pdo->prepare("
    SELECT c.name AS category, 
           c.type, 
           SUM(t.amount) AS total 
    FROM transactions t
    JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = ? 
      AND MONTH(t.date) = ? 
      AND YEAR(t.date) = ?
    GROUP BY c.id, c.type
");
$stmt->execute([$_SESSION['user_id'], $month, $year]);
$data = $stmt->fetchAll();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = ['Danh mục', 'Loại', 'Tổng tiền'];
$sheet->fromArray($headers, null, 'A1');

$row = 2;
foreach ($data as $item) {
    $sheet->setCellValue('A'.$row, $item['category']);
    $sheet->setCellValue('B'.$row, $item['type'] == 'income' ? 'Thu nhập' : 'Chi tiêu');
    $sheet->setCellValue('C'.$row, $item['total']);
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="bao_cao_'.$year.'_'.$month.'.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;