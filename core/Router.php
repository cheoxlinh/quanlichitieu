<?php
class Router {
    protected $controller = 'TransactionController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // --- PHẦN SỬA LỖI ---
        // Sửa logic để tìm controller cho đúng
        if (!empty($url[0])) {
            // Chuyển đổi tên số nhiều thành số ít (ví dụ: 'tags' -> 'tag')
            // Bằng cách xóa chữ 's' ở cuối
            $singular = rtrim($url[0], 's'); 
            
            // Tạo tên controller đúng (ví dụ: 'TagController')
            $controllerName = ucfirst($singular) . 'Controller';

            // Kiểm tra xem tệp controller có tồn tại không
            if (file_exists(ROOT_PATH . '/app/Controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }
        // --- KẾT THÚC PHẦN SỬA ---

        $this->controller = new $this->controller;

        // Xác định Method
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // Lấy Params
        $this->params = $url ? array_values($url) : [];

        // Gọi controller và method tương ứng
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = str_replace(BASE_URL, '', $_SERVER['REQUEST_URI']);
            $requestUri = strtok($requestUri, '?');
            
            return explode('/', filter_var(trim($requestUri, '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}