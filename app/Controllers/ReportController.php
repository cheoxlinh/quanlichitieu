<?php
class ReportController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $transactionModel = $this->model('Transaction');
        $settingModel = $this->model('Setting');
        
        $settings = $settingModel->getAllSettings();
        $exchange_rate = $settings['usd_to_vnd_rate'] ?? 26500;

        // Gọi phương thức mới để lấy dữ liệu thống kê
        $monthlyData = $transactionModel->getMonthlySummary($exchange_rate);

        $this->view('reports/index', [
            'monthlyData' => $monthlyData,
            'settings' => $settings
        ]);
    }
}