<?php
class Router {
    protected $controller = 'TransactionController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Xác định Controller
        if (!empty($url[0]) && file_exists('../app/Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }
        require_once '../app/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Xác định Method
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // Lấy Params
        $this->params = $url ? array_values($url) : [];

        // Gọi controller, method với các tham số
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_SERVER['REQUEST_URI'])) {
            // Loại bỏ phần base path của dự án khỏi URL
            $basePath = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
            $requestUri = str_replace($basePath, '', $_SERVER['REQUEST_URI']);
            
            return explode('/', filter_var(trim($requestUri, '/'), FILTER_SANITIZE_URL));
        }
    }
}