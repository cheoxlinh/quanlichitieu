<?php
class SettingController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $settingModel = $this->model('Setting');
        $settings = $settingModel->getAllSettings();
        $this->view('settings/index', ['settings' => $settings]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $settingModel = $this->model('Setting');
            
            if (isset($_POST['currency'])) {
                $settingModel->updateSetting('currency', $_POST['currency']);
            }
            if (isset($_POST['usd_to_vnd_rate'])) {
                $settingModel->updateSetting('usd_to_vnd_rate', $_POST['usd_to_vnd_rate']);
            }

            header('Location: ' . BASE_URL . '/setting');
            exit();
        }
    }

     // --- THÊM PHƯƠNG THỨC MỚI DƯỚI ĐÂY ---
    public function updateFromApi() {
        // *** THAY THẾ 'YOUR_API_KEY' BẰNG API KEY CỦA BẠN ***
        $apiKey = 'ca3b5853e836f942036d3319';
        $apiUrl = "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD";

        // Sử dụng @ để bỏ qua cảnh báo nếu API lỗi, chúng ta sẽ tự xử lý lỗi
        $jsonData = @file_get_contents($apiUrl);

        if ($jsonData === false) {
            $_SESSION['flash_message'] = 'Lỗi: Không thể kết nối đến API tỉ giá.';
        } else {
            $data = json_decode($jsonData);
            if ($data && $data->result === 'success' && isset($data->conversion_rates->VND)) {
                $newRate = $data->conversion_rates->VND;
                
                $settingModel = $this->model('Setting');
                $settingModel->updateSetting('usd_to_vnd_rate', $newRate);
                
                $_SESSION['flash_message'] = 'Thành công: Tỉ giá đã được cập nhật thành ' . $newRate . '.';
            } else {
                $_SESSION['flash_message'] = 'Lỗi: Không thể lấy được tỉ giá VND từ API.';
            }
        }
        
        // Quay trở lại trang cài đặt
        header('Location: ' . BASE_URL . '/setting');
        exit();
    }
}