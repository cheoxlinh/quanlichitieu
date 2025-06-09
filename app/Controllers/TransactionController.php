<?php
class TransactionController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }
    
    public function index() {
        $transactionModel = $this->model('Transaction');
        $tagModel = $this->model('Tag');
        $settingModel = $this->model('Setting');

        $filters = $_GET;
        $tags = $tagModel->findAll();
        $settings = $settingModel->getAllSettings();

        // Logic phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $records_per_page = 10; // Số giao dịch trên mỗi trang
        $offset = ($page - 1) * $records_per_page;

        $total_records = $transactionModel->countAll($filters);
        $total_pages = ceil($total_records / $records_per_page);
        // Thêm dòng này để lấy tổng số tiền
        $total_amount = $transactionModel->sumAll($filters, $settings['usd_to_vnd_rate']);
        $transactions = $transactionModel->findAll($filters, $records_per_page, $offset);

        $this->view('transactions/index', [
            'transactions' => $transactions,
            'tags' => $tags,
            'settings' => $settings,
            'filters' => $filters,
            'total_pages' => $total_pages,
            'current_page' => $page,
            'total_amount' => $total_amount
        ]);
    }
    
    // Các hàm còn lại giữ nguyên
    public function create() {
        $tagModel = $this->model('Tag');
        $settingModel = $this->model('Setting'); // Thêm dòng này
        $tags = $tagModel->findAll();
        $settings = $settingModel->getAllSettings(); // Thêm dòng này
        $this->view('transactions/create', ['tags' => $tags, 'settings' => $settings]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $transactionModel = $this->model('Transaction');
            $transactionModel->create($_POST);
            header('Location: ' . BASE_URL . '/transactions');
        }
    }

    public function edit($id) {
        $transactionModel = $this->model('Transaction');
        $tagModel = $this->model('Tag');
        $settingModel = $this->model('Setting'); // Thêm dòng này
        $transaction = $transactionModel->findById($id);
        $tags = $tagModel->findAll();
        $settings = $settingModel->getAllSettings(); // Thêm dòng này
        $this->view('transactions/edit', [
            'transaction' => $transaction,
            'tags' => $tags,
            'settings' => $settings // Truyền settings sang view
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $transactionModel = $this->model('Transaction');
            $transactionModel->update($id, $_POST);
            header('Location: ' . BASE_URL . '/transactions');
        }
    }

    // Sửa lại phương thức destroy()
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $transactionModel = $this->model('Transaction');
            $transactionModel->delete($id);
            // Cập nhật lại lệnh chuyển hướng
            header('Location: ' . BASE_URL . '/transactions');
            exit(); // Thêm exit() để đảm bảo dừng thực thi ngay lập tức
        }
    }
    
}