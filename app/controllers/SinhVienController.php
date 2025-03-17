<?php
require_once('app/config/database.php');
require_once('app/models/SinhVienModel.php');
require_once('app/models/NganhModel.php');

class SinhVienController
{
    private $sinhVienModel;
    private $db;
    private $baseUrl = '/baitap/dangkyhocphan/';

    public function __construct()
    {
        $this->db = (new Database()) ->getConnection();
        $this->sinhVienModel = new SinhVienModel($this->db);
    }

    public function index()
    {
        $sinhviens = $this->sinhVienModel->getSinhViens();
        include 'app/views/sinhvien/listsinhvien.php';
    }

    public function show($id)
    {
        $sinhvien = $this->sinhVienModel->getSinhVienById($id);
        if($sinhvien){
            include 'app/views/sinhvien/showsinhvien.php';
        } else{
            echo "Không thấy sinh viên";
        }
    }

    public function add()
    {
        $nganhs = (new NganhModel($this->db))->getNganhs();
        include_once 'app/views/sinhvien/addsinhvien.php';
    }

    public function save()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $maSV = $_POST['maSV'] ?? '';
            $hoTen = $_POST['hoTen'] ?? '';
            $gioiTinh = $_POST['gioiTinh'] ?? '';
            $ngaySinh = $_POST['ngaySinh'] ?? '';
            $maNganh = $_POST['maNganh'] ?? null;
            $hinh = isset($_FILES['hinh']) ? $_FILES['hinh'] : null;

            $result = $this->sinhVienModel->addSinhVien($maSV, $hoTen, $gioiTinh, $ngaySinh, $maNganh, $hinh);

            if(is_array($result)){
                $errors = $result;
                $nganhs = (new NganhModel($this->db))->getNganhs();
                include 'app/views/sinhvien/addsinhvien.php';
            }else{
                header('Location: ' . $this->baseUrl . 'SinhVien');
            }
        }
    }

    public function edit($id)
    {
        $sinhvien = $this->sinhVienModel->getSinhVienById($id);
        $nganhs = (new NganhModel($this->db))->getNganhs();

        if($sinhvien){
            include 'app/views/sinhvien/editsinhvien.php';
        }else{
            echo "Không thấy sinh viên";
        }
    }

    public function update()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $maSV = $_POST['maSV'];
            $hoTen = $_POST['hoTen'];
            $gioiTinh = $_POST['gioiTinh'];
            $ngaySinh = $_POST['ngaySinh'];
            $maNganh = $_POST['maNganh'];
            $hinh = isset($_FILES['hinh']) && $_FILES['hinh']['error'] != 4 ? $_FILES['hinh'] : null;

            $edit = $this->sinhVienModel->updateSinhVien($maSV, $hoTen, $gioiTinh, $ngaySinh, $maNganh, $hinh);

            if($edit){
                header('Location: ' . $this->baseUrl . 'SinhVien');
            }else{
                echo "Đã xảy ra lỗi khi lưu sinh viên.";
            }
        }
    }
    
    public function delete($id)
    {
        if($this->sinhVienModel->deleteSinhVien($id)){
            header('Location: ' . $this->baseUrl . 'SinhVien');
        }else{
            echo "Đã xảy ra lỗi khi xóa sinh viên.";
        }
    }
}
?>