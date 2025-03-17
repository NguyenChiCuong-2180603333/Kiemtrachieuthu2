<?php
require_once('app/config/database.php');
require_once('app/models/SinhVienModel.php');

class AuthController
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
        
        if(isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl . 'HocPhan');
            exit();
        }
        
        include 'app/views/auth/login.php';
    }

    public function login()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maSV = $_POST['maSV'] ?? '';
            $remember = isset($_POST['remember']);
            
        
            $sinhvien = $this->sinhVienModel->getSinhVienById($maSV);
            
            // Nếu tìm thấy sinh viên, cho phép đăng nhập
            if($sinhvien) {
                
                $_SESSION['user_id'] = $sinhvien->MaSV;
                $_SESSION['user_name'] = $sinhvien->HoTen;
                
              
                if($remember) {
                    setcookie('user_id', $sinhvien->MaSV, time() + (86400 * 30), "/");
                }
                
                header('Location: ' . $this->baseUrl . 'HocPhan');
                exit();
            } else {
               
                $error_message = "Mã sinh viên không tồn tại trong hệ thống";
                include 'app/views/auth/login.php';
            }
        }
    }

    public function logout()
    {
        
        session_unset();
        session_destroy();
        
      
        if(isset($_COOKIE['user_id'])) {
            setcookie('user_id', '', time() - 3600, "/");
        }
        
        header('Location: ' . $this->baseUrl . 'Auth');
        exit();
    }
}
?>