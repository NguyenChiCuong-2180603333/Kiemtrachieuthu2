<?php
require_once('app/config/database.php');
require_once('app/models/NganhModel.php');

class NganhController
{
    private $nganhModel;
    private $db;
    private $baseUrl = '/baitap/dangkyhocphan/';

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->nganhModel = new NganhModel($this->db);
    }

    public function index()
    {
        $nganhs = $this->nganhModel->getNganhs();
        include 'app/views/nganh/listnganh.php';
    }

    public function add()
    {
        include 'app/views/nganh/addnganh.php';
    }

    public function save()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $maNganh = $_POST['maNganh'] ?? '';
            $tenNganh = $_POST['tenNganh'] ?? '';

            $result = $this->nganhModel->addNganh($maNganh, $tenNganh);

            if(is_array($result)){
                $errors = $result;
                include 'app/views/nganh/addnganh.php';
            }else{
                header('Location: ' . $this->baseUrl . 'Nganh');
            }
        }
    }

    public function edit($id)
    {
        $nganh = $this->nganhModel->getNganhById($id);

        if($nganh){
            include 'app/views/nganh/editnganh.php';
        }else{
            echo "Không tìm thấy ngành học";
        }
    }

    public function update()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $maNganh = $_POST['maNganh'];
            $tenNganh = $_POST['tenNganh'];

            $edit = $this->nganhModel->updateNganh($maNganh, $tenNganh);

            if($edit){
                header('Location: ' . $this->baseUrl . 'Nganh');
            }else{
                echo "Đã xảy ra lỗi khi lưu ngành học.";
            }
        }
    }

    public function delete($id)
    {
        if($this->nganhModel->deleteNganh($id)){
            header('Location: ' . $this->baseUrl . 'Nganh');
        }else{
            echo "Đã xảy ra lỗi khi xóa ngành học.";
        }
    }
}
?>