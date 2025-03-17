<?php
// Bật báo cáo lỗi cho phát triển
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Khởi tạo session
session_start();

// Define base application path
define('APP_PATH', __DIR__);

// Parse the URL
$request_uri = $_SERVER['REQUEST_URI'];

// Xử lý đường dẫn - kiểm tra "/baitap/dangkyhocphan"
if (strpos($request_uri, '/baitap/dangkyhocphan') === 0) {
    $base_path = '/baitap/dangkyhocphan/';
    $path = substr($request_uri, strlen('/baitap/dangkyhocphan/'));
} else if (strpos($request_uri, '/dangkyhocphan') === 0) {
    $base_path = '/dangkyhocphan/';
    $path = substr($request_uri, strlen('/dangkyhocphan/'));
} else {
    $base_path = '/';
    $path = trim($request_uri, '/');
}

// Phân tích đường dẫn
$url_parts = explode('/', $path);

// Set defaults
$controller = empty($url_parts[0]) ? 'Default' : ucfirst($url_parts[0]);
$action = isset($url_parts[1]) && !empty($url_parts[1]) ? $url_parts[1] : 'index';
$params = array_slice($url_parts, 2);

// Load the controller
$controller_file = "app/controllers/{$controller}Controller.php";

if(file_exists($controller_file)) {
    require_once($controller_file);
    $controller_class = "{$controller}Controller";
    $controller_instance = new $controller_class();
    
    // Check if the action method exists
    if(method_exists($controller_instance, $action)) {
        // Call the action method with parameters
        call_user_func_array([$controller_instance, $action], $params);
    } else {
        // Action doesn't exist
        require_once("app/controllers/DefaultController.php");
        $default = new DefaultController();
        $default->notFound();
    }
} else {
    // Controller doesn't exist
    require_once("app/controllers/DefaultController.php");
    $default = new DefaultController();
    $default->notFound();
}
?>