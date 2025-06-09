<?php
class TransactionController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }
    
    public function index() {
        $transactionModel = $this->model('Transaction');
        $tagModel = $this->model('Tag');
        
        $filters = $_GET;
        $transactions = $transactionModel->findAll($filters);
        $tags = $tagModel->findAll();

        $this->view('transactions/index', [
            'transactions' => $transactions,
            'tags' => $tags,
            'filters' => $filters
        ]);
    }
    
    public function create() {
        $tagModel = $this->model('Tag');
        $tags = $tagModel->findAll();
        $this->view('transactions/create', ['tags' => $tags]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $transactionModel = $this->model('Transaction');
            $transactionModel->create($_POST);
            header('Location: ../transactions');
        }
    }

    public function edit($id) {
        $transactionModel = $this->model('Transaction');
        $tagModel = $this->model('Tag');
        
        $transaction = $transactionModel->findById($id);
        $tags = $tagModel->findAll();
        
        $this->view('transactions/edit', [
            'transaction' => $transaction,
            'tags' => $tags
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $transactionModel = $this->model('Transaction');
            $transactionModel->update($id, $_POST);
            header('Location: ../../transactions');
        }
    }

    public function destroy($id) {
         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $transactionModel = $this->model('Transaction');
            $transactionModel->delete($id);
            header('Location: ../../transactions');
        }
    }
}