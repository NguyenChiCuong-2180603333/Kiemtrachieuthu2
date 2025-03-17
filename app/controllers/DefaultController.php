<?php
require_once('app/config/database.php');
require_once('app/models/SinhVienModel.php');

class DefaultController
{
    private $sinhVienModel;
    private $db;
    private $baseUrl = '/baitap/dangkyhocphan/';

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->sinhVienModel = new SinhVienModel($this->db);
    }

    public function index()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (isset($_SESSION['user_id'])) {
            // Nếu đã đăng nhập, chuyển hướng đến trang học phần
            header('Location: ' . $this->baseUrl . 'HocPhan');
        } else {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            header('Location: ' . $this->baseUrl . 'Auth');
        }
        exit();
    }

    // 404 error handling
    public function notFound()
    {
        include 'app/views/errors/404.php';
    }
}
?>