<?php
class HocPhanModel
{
    private $conn;
    private $table_name = "HocPhan";

    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function getHocPhans()
    {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch(PDOException $e) {
            echo "Lỗi getHocPhans: " . $e->getMessage();
            return [];
        }
    }

    public function getHocPhanById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE MaHP = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return $result;
        } catch(PDOException $e) {
            echo "Lỗi getHocPhanById: " . $e->getMessage();
            return null;
        }
    }

    public function addHocPhan($maHP, $tenHP, $soTinChi, $soLuongDuKien = 0)
    {
        $errors = [];
        if (empty($maHP)) {
            $errors['maHP'] = 'Mã học phần không được để trống';
        }
        if (empty($tenHP)) {
            $errors['tenHP'] = 'Tên học phần không được để trống';
        }
        if (!is_numeric($soTinChi) || $soTinChi <= 0) {
            $errors['soTinChi'] = 'Số tín chỉ phải là số dương';
        }
        
        if(count($errors) > 0) {
            return $errors;
        }
        
        try {
            $query = "INSERT INTO " . $this->table_name . " (MaHP, TenHP, SoTinChi, SoLuongDuKien) 
                     VALUES (:maHP, :tenHP, :soTinChi, :soLuongDuKien)";
            $stmt = $this->conn->prepare($query);

            $maHP = htmlspecialchars(strip_tags($maHP));
            $tenHP = htmlspecialchars(strip_tags($tenHP));
            $soTinChi = htmlspecialchars(strip_tags($soTinChi));
            $soLuongDuKien = htmlspecialchars(strip_tags($soLuongDuKien));

            $stmt->bindParam(':maHP', $maHP);
            $stmt->bindParam(':tenHP', $tenHP);
            $stmt->bindParam(':soTinChi', $soTinChi);
            $stmt->bindParam(':soLuongDuKien', $soLuongDuKien);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi addHocPhan: " . $e->getMessage();
            return false;
        }
    }

    public function updateHocPhan($maHP, $tenHP, $soTinChi, $soLuongDuKien = 0)
    {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET TenHP=:tenHP, SoTinChi=:soTinChi, SoLuongDuKien=:soLuongDuKien 
                     WHERE MaHP=:maHP";
            $stmt = $this->conn->prepare($query);

            $maHP = htmlspecialchars(strip_tags($maHP));
            $tenHP = htmlspecialchars(strip_tags($tenHP));
            $soTinChi = htmlspecialchars(strip_tags($soTinChi));
            $soLuongDuKien = htmlspecialchars(strip_tags($soLuongDuKien));

            $stmt->bindParam(':maHP', $maHP);
            $stmt->bindParam(':tenHP', $tenHP);
            $stmt->bindParam(':soTinChi', $soTinChi);
            $stmt->bindParam(':soLuongDuKien', $soLuongDuKien);

            if($stmt->execute()){
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi updateHocPhan: " . $e->getMessage();
            return false;
        }
    }
    
    public function deleteHocPhan($id)
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE MaHP=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            
            if($stmt->execute()){
                return true;
            }     
            return false;
        } catch(PDOException $e) {
            echo "Lỗi deleteHocPhan: " . $e->getMessage();
            return false;
        }
    }
    
    public function capNhatSoLuongDuKien($maHP, $soLuong)
    {
        try {
            $query = "UPDATE " . $this->table_name . " SET SoLuongDuKien = :soLuong WHERE MaHP = :maHP";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':soLuong', $soLuong);
            $stmt->bindParam(':maHP', $maHP);
            
            if($stmt->execute()){
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi capNhatSoLuongDuKien: " . $e->getMessage();
            return false;
        }
    }
    
    public function giamSoLuongDuKien($maHP)
    {
        try {
            $hocPhan = $this->getHocPhanById($maHP);
            if($hocPhan && $hocPhan->SoLuongDuKien > 0) {
                $soLuongMoi = $hocPhan->SoLuongDuKien - 1;
                return $this->capNhatSoLuongDuKien($maHP, $soLuongMoi);
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi giamSoLuongDuKien: " . $e->getMessage();
            return false;
        }
    }
    
    public function tangSoLuongDuKien($maHP)
    {
        try {
            $hocPhan = $this->getHocPhanById($maHP);
            if($hocPhan) {
                $soLuongMoi = $hocPhan->SoLuongDuKien + 1;
                return $this->capNhatSoLuongDuKien($maHP, $soLuongMoi);
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi tangSoLuongDuKien: " . $e->getMessage();
            return false;
        }
    }
}
?>