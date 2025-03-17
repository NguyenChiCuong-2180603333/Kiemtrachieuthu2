<?php
require_once('app/config/database.php');
require_once('app/models/HocPhanModel.php');

class HocPhanController
{
    private $hocPhanModel;
    private $db;
    private $baseUrl = '/baitap/dangkyhocphan/';

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->hocPhanModel = new HocPhanModel($this->db);
        
        // Khởi tạo session nếu chưa có
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        
        $this->checkLogin();
        
        $hocphans = $this->hocPhanModel->getHocPhans();
        include 'app/views/hocphan/listhocphan.php';
    }
    
    private function checkLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl . 'Auth');
            exit();
        }
    }
    
    public function add()
{
    $this->checkLogin();
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Lấy mã học phần từ query string
    $maHP = isset($_GET['id']) ? $_GET['id'] : null;
        
        if ($maHP) {
            // Kiểm tra xem học phần đã tồn tại trong giỏ chưa
            if (!isset($_SESSION['cart'][$maHP])) {
                $hocphan = $this->hocPhanModel->getHocPhanById($maHP);
                
                if ($hocphan) {
                    $_SESSION['cart'][$maHP] = array(
                        'MaHP' => $hocphan->MaHP,
                        'TenHP' => $hocphan->TenHP,
                        'SoTinChi' => $hocphan->SoTinChi
                    );
                    
                    
                    $_SESSION['message'] = "Đã thêm học phần '{$hocphan->TenHP}' vào giỏ đăng ký";
                }
            } else {
                
                $_SESSION['message'] = "Học phần này đã có trong giỏ đăng ký";
            }
        }
        
        
        header('Location: ' . $this->baseUrl . 'HocPhan/cart');
        exit();
    }
    
    public function cart()
    {
        
        $this->checkLogin();
        
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        include 'app/views/hocphan/cart.php';
    }
    
    public function remove()
    {
        
        $this->checkLogin();
        
        
        $maHP = isset($_GET['id']) ? $_GET['id'] : null;
        
        if ($maHP && isset($_SESSION['cart'][$maHP])) {
           
            $tenHP = $_SESSION['cart'][$maHP]['TenHP'];
            
          
            unset($_SESSION['cart'][$maHP]);
            
           
            $_SESSION['message'] = "Đã xóa học phần '{$tenHP}' khỏi giỏ đăng ký";
        }
        
        
        header('Location: ' . $this->baseUrl . 'HocPhan/cart');
        exit();
    }
    
    public function clear()
    {
       
        $this->checkLogin();
        
        // Xóa toàn bộ giỏ đăng ký
        $_SESSION['cart'] = array();
        
        
        $_SESSION['message'] = "Đã xóa toàn bộ giỏ đăng ký";
        
        
        header('Location: ' . $this->baseUrl . 'HocPhan/cart');
        exit();
    }
    
    public function save()
    {
       
        $this->checkLogin();
        
        // Kiểm tra giỏ có học phần nào không
        if (empty($_SESSION['cart'])) {
            $_SESSION['message'] = "Giỏ đăng ký trống. Vui lòng chọn ít nhất một học phần.";
            header('Location: ' . $this->baseUrl . 'HocPhan/cart');
            exit();
        }
        
        // Lưu thông tin đăng ký vào database
        require_once('app/models/DangKyModel.php');
        $dangKyModel = new DangKyModel($this->db);
        
        
        $maSV = $_SESSION['user_id'];
        
       
        $maDK = $dangKyModel->addDangKy($maSV);
        
        if ($maDK) {
          
            $success = true;
            
            foreach ($_SESSION['cart'] as $hocphan) {
               
                if (!$dangKyModel->addChiTietDangKy($maDK, $hocphan['MaHP'])) {
                    $success = false;
                } else {
                    $this->hocPhanModel->giamSoLuongDuKien($hocphan['MaHP']);
                }
            }
            
            if ($success) {
                // Xóa giỏ đăng ký
                $_SESSION['cart'] = array();
                
            
                $_SESSION['message'] = "Đăng ký học phần thành công!";
                
                header('Location: ' . $this->baseUrl . 'HocPhan');
                exit();
            } else {
                $_SESSION['message'] = "Có lỗi xảy ra khi đăng ký học phần. Vui lòng thử lại.";
            }
        } else {
            $_SESSION['message'] = "Có lỗi xảy ra khi tạo đăng ký. Vui lòng thử lại.";
        }
        
        
        header('Location: ' . $this->baseUrl . 'HocPhan/cart');
        exit();
    }
}
?>