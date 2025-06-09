<?php
class TagController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $tagModel = $this->model('Tag');
        $tags = $tagModel->findAll();
        $this->view('tags/index', ['tags' => $tags]);
    }
    
    public function create() {
        $this->view('tags/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tagModel = $this->model('Tag');
            $tagModel->create($_POST);
            header('Location: ../tags');
        }
    }
    
    public function edit($id) {
        $tagModel = $this->model('Tag');
        $tag = $tagModel->findById($id);
        $this->view('tags/edit', ['tag' => $tag]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tagModel = $this->model('Tag');
            $tagModel->update($id, $_POST);
            header('Location: ../../tags');
        }
    }
    
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tagModel = $this->model('Tag');
            $tagModel->delete($id);
            header('Location: ../../tags');
        }
    }
}