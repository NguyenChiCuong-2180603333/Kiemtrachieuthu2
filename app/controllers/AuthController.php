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
        // Nếu đã đăng nhập, chuyển đến trang chủ
        if(isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl . 'HocPhan');
            exit();
        }
        
        // Hiển thị form đăng nhập
        include 'app/views/auth/login.php';
    }

    public function login()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maSV = $_POST['maSV'] ?? '';
            $remember = isset($_POST['remember']);
            
            // Lấy thông tin sinh viên từ database
            $sinhvien = $this->sinhVienModel->getSinhVienById($maSV);
            
            // Nếu tìm thấy sinh viên, cho phép đăng nhập
            if($sinhvien) {
                // Lưu thông tin đăng nhập vào session
                $_SESSION['user_id'] = $sinhvien->MaSV;
                $_SESSION['user_name'] = $sinhvien->HoTen;
                
                // Nếu người dùng chọn "Ghi nhớ đăng nhập"
                if($remember) {
                    // Tạo cookie lưu thông tin trong 30 ngày
                    setcookie('user_id', $sinhvien->MaSV, time() + (86400 * 30), "/");
                }
                
                // Chuyển hướng đến trang danh sách học phần
                header('Location: ' . $this->baseUrl . 'HocPhan');
                exit();
            } else {
                // Thông báo lỗi
                $error_message = "Mã sinh viên không tồn tại trong hệ thống";
                include 'app/views/auth/login.php';
            }
        }
    }

    public function logout()
    {
        // Xóa session
        session_unset();
        session_destroy();
        
        // Xóa cookie
        if(isset($_COOKIE['user_id'])) {
            setcookie('user_id', '', time() - 3600, "/");
        }
        
        // Chuyển hướng về trang đăng nhập
        header('Location: ' . $this->baseUrl . 'Auth');
        exit();
    }
}
?>