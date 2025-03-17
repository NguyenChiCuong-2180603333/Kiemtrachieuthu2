<?php
class SinhVienModel
{
    private $conn;
    private $table_name = "SinhVien";
    private $upload_dir = "uploads/sinhvien/";

    public function __construct($db)
    {
        $this->conn = $db;
        
        // Thêm đoạn code để đảm bảo thư mục upload tồn tại
        if (!file_exists($this->upload_dir)) {
            mkdir($this->upload_dir, 0777, true);
        }
    }
    
    public function getSinhViens()
    {
        try {
            $query = "SELECT sv.MaSV, sv.HoTen, sv.GioiTinh, sv.NgaySinh, sv.Hinh, sv.MaNganh, n.TenNganh as TenNganh
                      FROM " . $this->table_name . " sv 
                      LEFT JOIN NganhHoc n ON sv.MaNganh = n.MaNganh";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch(PDOException $e) {
            echo "Lỗi getSinhViens: " . $e->getMessage();
            return [];
        }
    }

    public function getSinhVienById($id)
    {
        try {
            $query = "SELECT sv.*, n.TenNganh 
                     FROM " . $this->table_name . " sv
                     LEFT JOIN NganhHoc n ON sv.MaNganh = n.MaNganh 
                     WHERE sv.MaSV = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return $result;
        } catch(PDOException $e) {
            echo "Lỗi getSinhVienById: " . $e->getMessage();
            return null;
        }
    }

    public function addSinhVien($maSV, $hoTen, $gioiTinh, $ngaySinh, $maNganh, $hinh = null)
    {
        $errors = [];
        if (empty($maSV)) {
            $errors['maSV'] = 'Mã sinh viên không được để trống';
        }
        if (empty($hoTen)) {
            $errors['hoTen'] = 'Họ tên không được để trống';
        }
        
        // Xử lý upload hình ảnh
        $hinh_name = "";
        if ($hinh && $hinh['error'] == 0) {
            $hinh_info = pathinfo($hinh['name']);
            $hinh_ext = strtolower($hinh_info['extension']);
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");
            
            if (!in_array($hinh_ext, $allowed_extensions)) {
                $errors['hinh'] = 'Chỉ cho phép tải lên các file hình ảnh (jpg, jpeg, png, gif)';
            } else {
                $hinh_name = time() . '_' . basename($hinh['name']);
                $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/baitap/dangkyhocphan/' . $this->upload_dir;
                
                // Đảm bảo thư mục tồn tại
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $target_file = $target_dir . $hinh_name;
                
                if (!move_uploaded_file($hinh['tmp_name'], $target_file)) {
                    $errors['hinh'] = 'Có lỗi xảy ra khi tải lên hình ảnh';
                    error_log("Không thể upload file đến: " . $target_file);
                }
            }
        }
        
        if(count($errors) > 0) {
            return $errors;
        }
        
        try {
            $query = "INSERT INTO " . $this->table_name . " (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                     VALUES (:maSV, :hoTen, :gioiTinh, :ngaySinh, :hinh, :maNganh)";
            $stmt = $this->conn->prepare($query);

            $maSV = htmlspecialchars(strip_tags($maSV));
            $hoTen = htmlspecialchars(strip_tags($hoTen));
            $gioiTinh = htmlspecialchars(strip_tags($gioiTinh));
            $maNganh = htmlspecialchars(strip_tags($maNganh));

            $stmt->bindParam(':maSV', $maSV);
            $stmt->bindParam(':hoTen', $hoTen);
            $stmt->bindParam(':gioiTinh', $gioiTinh);
            $stmt->bindParam(':ngaySinh', $ngaySinh);
            $stmt->bindParam(':hinh', $hinh_name);
            $stmt->bindParam(':maNganh', $maNganh);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi addSinhVien: " . $e->getMessage();
            return false;
        }
    }

    public function updateSinhVien($maSV, $hoTen, $gioiTinh, $ngaySinh, $maNganh, $hinh = null)
    {
        try {
            // Lấy thông tin sinh viên hiện tại
            $current_sv = $this->getSinhVienById($maSV);
            $hinh_name = $current_sv->Hinh;
            
            // Xử lý upload hình ảnh mới (nếu có)
            if ($hinh && $hinh['error'] == 0) {
                $hinh_info = pathinfo($hinh['name']);
                $hinh_ext = strtolower($hinh_info['extension']);
                $allowed_extensions = array("jpg", "jpeg", "png", "gif");
                
                if (in_array($hinh_ext, $allowed_extensions)) {
                    // Xóa hình ảnh cũ nếu có
                    $old_file = $_SERVER['DOCUMENT_ROOT'] . '/baitap/dangkyhocphan/' . $this->upload_dir . $hinh_name;
                    if (!empty($hinh_name) && file_exists($old_file)) {
                        unlink($old_file);
                    }
                    
                    // Lưu hình ảnh mới
                    $hinh_name = time() . '_' . basename($hinh['name']);
                    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/baitap/dangkyhocphan/' . $this->upload_dir;
                    
                    // Đảm bảo thư mục tồn tại
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }
                    
                    $target_file = $target_dir . $hinh_name;
                    move_uploaded_file($hinh['tmp_name'], $target_file);
                }
            }
            
            $query = "UPDATE " . $this->table_name . " 
                     SET HoTen=:hoTen, GioiTinh=:gioiTinh, NgaySinh=:ngaySinh, Hinh=:hinh, MaNganh=:maNganh 
                     WHERE MaSV=:maSV";
            $stmt = $this->conn->prepare($query);

            $hoTen = htmlspecialchars(strip_tags($hoTen));
            $gioiTinh = htmlspecialchars(strip_tags($gioiTinh));
            $maNganh = htmlspecialchars(strip_tags($maNganh));
            $maSV = htmlspecialchars(strip_tags($maSV));

            $stmt->bindParam(':maSV', $maSV);
            $stmt->bindParam(':hoTen', $hoTen);
            $stmt->bindParam(':gioiTinh', $gioiTinh);
            $stmt->bindParam(':ngaySinh', $ngaySinh);
            $stmt->bindParam(':hinh', $hinh_name);
            $stmt->bindParam(':maNganh', $maNganh);

            if($stmt->execute()){
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi updateSinhVien: " . $e->getMessage();
            return false;
        }
    }

    public function deleteSinhVien($id)
    {
        try {
            // Lấy thông tin sinh viên trước khi xóa để lấy tên file hình ảnh
            $sinhvien = $this->getSinhVienById($id);
            
            $query = "DELETE FROM " . $this->table_name . " WHERE MaSV=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            
            if($stmt->execute()){
                // Xóa file hình ảnh nếu có
                $image_file = $_SERVER['DOCUMENT_ROOT'] . '/baitap/dangkyhocphan/' . $this->upload_dir . $sinhvien->Hinh;
                if ($sinhvien && !empty($sinhvien->Hinh) && file_exists($image_file)) {
                    unlink($image_file);
                }
                return true;
            }     
            return false;
        } catch(PDOException $e) {
            echo "Lỗi deleteSinhVien: " . $e->getMessage();
            return false;
        }
    }
}