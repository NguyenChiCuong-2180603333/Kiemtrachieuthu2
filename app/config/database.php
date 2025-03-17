<?php
class Database {
    private $host = "localhost";
    private $db_name = "test1";  
    private $username = "root";
    private $password = ""; 
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
           
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
            
           
            $this->conn->query("SELECT 1");
            
        } catch(PDOException $exception) {
            echo "Lỗi kết nối: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
    
    // Phương thức kiểm tra xem database đã tồn tại chưa
    public function checkDatabase() {
        try {
            // Kết nối không có tên database
            $temp_conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $temp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Kiểm tra database có tồn tại không
            $result = $temp_conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$this->db_name}'");
            
            if($result->rowCount() == 0) {
                // Tạo database nếu chưa tồn tại
                $temp_conn->exec("CREATE DATABASE {$this->db_name}");
                return "Đã tạo database {$this->db_name}";
            } else {
                return "Database {$this->db_name} đã tồn tại";
            }
            
        } catch(PDOException $exception) {
            return "Lỗi kiểm tra/tạo database: " . $exception->getMessage();
        }
    }
}
?>