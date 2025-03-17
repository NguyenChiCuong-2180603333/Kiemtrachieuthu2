<?php
class NganhModel
{
    private $conn;
    private $table_name = "NganhHoc"; 

    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function getNganhs()
    {
        try {
            $query = "SELECT MaNganh, TenNganh FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch(PDOException $e) {
            echo "Lỗi getNganhs: " . $e->getMessage();
            return [];
        }
    }
    
    public function getNganhById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE MaNganh = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return $result;
        } catch(PDOException $e) {
            echo "Lỗi getNganhById: " . $e->getMessage();
            return null;
        }
    }
    
    public function addNganh($maNganh, $tenNganh)
    {
        $errors = [];
        if (empty($maNganh)){
            $errors['maNganh'] = 'Mã ngành không được để trống';
        }
        if (empty($tenNganh)){
            $errors['tenNganh'] = 'Tên ngành không được để trống';
        }
        
        if(count($errors) > 0){
            return $errors;
        }
        
        try {
            $query = "INSERT INTO " . $this->table_name . " (MaNganh, TenNganh) VALUES (:maNganh, :tenNganh)";
            $stmt = $this->conn->prepare($query);
            
            $maNganh = htmlspecialchars(strip_tags($maNganh));
            $tenNganh = htmlspecialchars(strip_tags($tenNganh));
            
            $stmt->bindParam(':maNganh', $maNganh);
            $stmt->bindParam(':tenNganh', $tenNganh);
            
            if ($stmt->execute()){
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi addNganh: " . $e->getMessage();
            return false;
        }
    }
    
    public function updateNganh($maNganh, $tenNganh)
    {
        try {
            $query = "UPDATE " . $this->table_name . " SET TenNganh=:tenNganh WHERE MaNganh=:maNganh";
            $stmt = $this->conn->prepare($query);
            
            $tenNganh = htmlspecialchars(strip_tags($tenNganh));
            $maNganh = htmlspecialchars(strip_tags($maNganh));
            
            $stmt->bindParam(':maNganh', $maNganh);
            $stmt->bindParam(':tenNganh', $tenNganh);
            
            if($stmt->execute()){
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi updateNganh: " . $e->getMessage();
            return false;
        }
    }
    
    public function deleteNganh($id)
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE MaNganh=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            if($stmt->execute()){
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Lỗi deleteNganh: " . $e->getMessage();
            return false;
        }
    }
}
?>