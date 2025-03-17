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
        // Kiểm tra đăng nhập
        $this->checkLogin();
        
        $hocphans = $this->hocPhanModel->getHocPhans();
        include 'app/views/hocphan/listhocphan.php';
    }
    
    private function checkLogin()
    {
        // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl . 'Auth');
            exit();
        }
    }
    
    // Phương thức mới xử lý đăng ký học phần qua POST
    public function add_post()
    {
        // Kiểm tra đăng nhập
        $this->checkLogin();
        
        // Debug thông tin
        error_log("HocPhanController::add_post() được gọi với id = " . (isset($_POST['id']) ? $_POST['id'] : 'không có'));
        
        // Khởi tạo giỏ đăng ký nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        // Lấy mã học phần từ form POST
        $maHP = isset($_POST['id']) ? $_POST['id'] : null;
        
        if ($maHP) {
            // Kiểm tra xem học phần đã tồn tại trong giỏ chưa
            if (!isset($_SESSION['cart'][$maHP])) {
                // Lấy thông tin học phần từ database
                $hocphan = $this->hocPhanModel->getHocPhanById($maHP);
                
                if ($hocphan) {
                    // Thêm học phần vào giỏ
                    $_SESSION['cart'][$maHP] = array(
                        'MaHP' => $hocphan->MaHP,
                        'TenHP' => $hocphan->TenHP,
                        'SoTinChi' => $hocphan->SoTinChi
                    );
                    
                    // Thông báo thành công
                    $_SESSION['message'] = "Đã thêm học phần '{$hocphan->TenHP}' vào giỏ đăng ký";
                } else {
                    // Thông báo học phần không tồn tại
                    $_SESSION['message'] = "Không tìm thấy học phần có mã '{$maHP}'";
                    error_log("Không tìm thấy học phần có mã '{$maHP}'");
                }
            } else {
                // Thông báo học phần đã có trong giỏ
                $_SESSION['message'] = "Học phần này đã có trong giỏ đăng ký";
            }
        } else {
            // Thông báo thiếu mã học phần
            $_SESSION['message'] = "Không có mã học phần được cung cấp";
        }
        
        // Chuyển hướng về trang giỏ đăng ký
        header('Location: ' . $this->baseUrl . 'HocPhan/cart');
        exit();
    }
    
    // Giữ nguyên phương thức add hiện tại để tương thích ngược
    public function add()
    {
        // Kiểm tra đăng nhập
        $this->checkLogin();
        
        // Debug thông tin
        error_log("HocPhanController::add() được gọi với id = " . (isset($_GET['id']) ? $_GET['id'] : 'không có'));
        
        // Khởi tạo giỏ đăng ký nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        // Lấy mã học phần
        $maHP = isset($_GET['id']) ? $_GET['id'] : null;
        
        if ($maHP) {
            // Kiểm tra xem học phần đã tồn tại trong giỏ chưa
            if (!isset($_SESSION['cart'][$maHP])) {
                // Lấy thông tin học phần từ database
                $hocphan = $this->hocPhanModel->getHocPhanById($maHP);
                
                if ($hocphan) {
                    // Thêm học phần vào giỏ
                    $_SESSION['cart'][$maHP] = array(
                        'MaHP' => $hocphan->MaHP,
                        'TenHP' => $hocphan->TenHP,
                        'SoTinChi' => $hocphan->SoTinChi
                    );
                    
                    // Thông báo thành công
                    $_SESSION['message'] = "Đã thêm học phần '{$hocphan->TenHP}' vào giỏ đăng ký";
                } else {
                    // Thông báo học phần không tồn tại
                    $_SESSION['message'] = "Không tìm thấy học phần có mã '{$maHP}'";
                    error_log("Không tìm thấy học phần có mã '{$maHP}'");
                }
            } else {
                // Thông báo học phần đã có trong giỏ
                $_SESSION['message'] = "Học phần này đã có trong giỏ đăng ký";
            }
        } else {
            // Thông báo thiếu mã học phần
            $_SESSION['message'] = "Không có mã học phần được cung cấp";
        }
        
        // Chuyển hướng về trang giỏ đăng ký (hoặc danh sách học phần)
        header('Location: ' . $this->baseUrl . 'HocPhan/cart');
        exit();
    }
    
    public function cart()
    {
        // Kiểm tra đăng nhập
        $this->checkLogin();
        
        // Khởi tạo giỏ đăng ký nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        include 'app/views/hocphan/cart.php';
    }
    
    public function remove()
    {
        // Kiểm tra đăng nhập
        $this->checkLogin();
        
        // Lấy mã học phần
        $maHP = isset($_GET['id']) ? $_GET['id'] : null;
        
        if ($maHP && isset($_SESSION['cart'][$maHP])) {
            // Lưu tên học phần trước khi xóa
            $tenHP = $_SESSION['cart'][$maHP]['TenHP'];
            
            // Xóa học phần khỏi giỏ
            unset($_SESSION['cart'][$maHP]);
            
            // Thông báo thành công
            $_SESSION['message'] = "Đã xóa học phần '{$tenHP}' khỏi giỏ đăng ký";
        }
        
        // Chuyển hướng về trang giỏ đăng ký
        header('Location: ' . $this->baseUrl . 'HocPhan/cart');
        exit();
    }
    
    public function clear()
    {
        // Kiểm tra đăng nhập
        $this->checkLogin();
        
        // Xóa toàn bộ giỏ đăng ký
        $_SESSION['cart'] = array();
        
        // Thông báo thành công
        $_SESSION['message'] = "Đã xóa toàn bộ giỏ đăng ký";
        
        // Chuyển hướng về trang giỏ đăng ký
        header('Location: ' . $this->baseUrl . 'HocPhan/cart');
        exit();
    }
    
    public function save()
    {
        // Kiểm tra đăng nhập
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
        
        // Lấy ID sinh viên từ session
        $maSV = $_SESSION['user_id'];
        
        // Tạo đăng ký mới
        $maDK = $dangKyModel->addDangKy($maSV);
        
        if ($maDK) {
            // Thêm chi tiết đăng ký
            $success = true;
            
            foreach ($_SESSION['cart'] as $hocphan) {
                // Thêm chi tiết và giảm số lượng dự kiến
                if (!$dangKyModel->addChiTietDangKy($maDK, $hocphan['MaHP'])) {
                    $success = false;
                } else {
                    // Giảm số lượng dự kiến sinh viên
                    $this->hocPhanModel->giamSoLuongDuKien($hocphan['MaHP']);
                }
            }
            
            if ($success) {
                // Xóa giỏ đăng ký
                $_SESSION['cart'] = array();
                
                // Thông báo thành công
                $_SESSION['message'] = "Đăng ký học phần thành công!";
                
                // Chuyển hướng về trang danh sách học phần
                header('Location: ' . $this->baseUrl . 'HocPhan');
                exit();
            } else {
                $_SESSION['message'] = "Có lỗi xảy ra khi đăng ký học phần. Vui lòng thử lại.";
            }
        } else {
            $_SESSION['message'] = "Có lỗi xảy ra khi tạo đăng ký. Vui lòng thử lại.";
        }
        
        // Nếu có lỗi, quay lại trang giỏ đăng ký
        header('Location: ' . $this->baseUrl . 'HocPhan/cart');
        exit();
    }
}
?>