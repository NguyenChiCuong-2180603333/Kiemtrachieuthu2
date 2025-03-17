<?php
class DangKyModel
{
    private $conn;
    private $table_name = "DangKy";
    private $detail_table = "ChiTietDangKy";

    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function getDangKyBySinhVien($maSV)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE MaSV = :maSV ORDER BY NgayDK DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maSV', $maSV);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch(PDOException $e) {
            echo "Lỗi getDangKyBySinhVien: " . $e->getMessage();
            return [];
        }
    }
    
    public function getDangKyById($maDK)
    {
        try {
            $query = "SELECT dk.*, sv.HoTen 
                     FROM " . $this->table_name . " dk
                     LEFT JOIN SinhVien sv ON dk.MaSV = sv.MaSV 
                     WHERE dk.MaDK = :maDK";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDK', $maDK);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return $result;
        } catch(PDOException $e) {
            echo "Lỗi getDangKyById: " . $e->getMessage();
            return null;
        }
    }
    
    public function getChiTietDangKy($maDK)
    {
        try {
            $query = "SELECT ct.*, hp.TenHP, hp.SoTinChi 
                     FROM " . $this->detail_table . " ct
                     LEFT JOIN HocPhan hp ON ct.MaHP = hp.MaHP 
                     WHERE ct.MaDK = :maDK";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDK', $maDK);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch(PDOException $e) {
            echo "Lỗi getChiTietDangKy: " . $e->getMessage();
            return [];
        }
    }
    
    public function addDangKy($maSV)
    {
        try {
            $query = "INSERT INTO " . $this->table_name . " (NgayDK, MaSV) 
                     VALUES (NOW(), :maSV)";
            $stmt = $this->conn->prepare($query);
            
            $maSV = htmlspecialchars(strip_tags($maSV));
            $stmt->bindParam(':maSV', $maSV);
            
            if ($stmt->execute()) {
                // Trả về ID của đăng ký vừa tạo
                return $this->conn->lastInsertId();
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi addDangKy: " . $e->getMessage();
            return false;
        }
    }
    
    public function addChiTietDangKy($maDK, $maHP)
    {
        try {
            $query = "INSERT INTO " . $this->detail_table . " (MaDK, MaHP) 
                     VALUES (:maDK, :maHP)";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':maDK', $maDK);
            $stmt->bindParam(':maHP', $maHP);
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi addChiTietDangKy: " . $e->getMessage();
            return false;
        }
    }
    
    public function deleteDangKy($maDK)
    {
        try {
            // Trước tiên, xóa các chi tiết đăng ký
            $query = "DELETE FROM " . $this->detail_table . " WHERE MaDK = :maDK";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDK', $maDK);
            $stmt->execute();
            
            // Sau đó, xóa đăng ký
            $query = "DELETE FROM " . $this->table_name . " WHERE MaDK = :maDK";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDK', $maDK);
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi deleteDangKy: " . $e->getMessage();
            return false;
        }
    }
    
    public function deleteChiTietDangKy($maDK, $maHP)
    {
        try {
            $query = "DELETE FROM " . $this->detail_table . " WHERE MaDK = :maDK AND MaHP = :maHP";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDK', $maDK);
            $stmt->bindParam(':maHP', $maHP);
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi deleteChiTietDangKy: " . $e->getMessage();
            return false;
        }
    }
}
?>