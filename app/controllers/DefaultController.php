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
            
            header('Location: ' . $this->baseUrl . 'HocPhan');
        } else {
            
            header('Location: ' . $this->baseUrl . 'Auth');
        }
        exit();
    }

    
    public function notFound()
    {
        include 'app/views/errors/404.php';
    }
}
?>