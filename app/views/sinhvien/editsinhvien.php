<?php include 'app/views/shares/header.php'; ?>

<h1>Sửa thông tin sinh viên</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/baitap/dangkyhocphan/SinhVien/update" enctype="multipart/form-data" onsubmit="return validateForm();">
    <input type="hidden" name="maSV" value="<?php echo $sinhvien->MaSV; ?>">

    <div class="form-group">
        <label for="hoTen">Họ tên:</label>
        <input type="text" id="hoTen" name="hoTen" class="form-control" 
               value="<?php echo htmlspecialchars($sinhvien->HoTen, ENT_QUOTES, 'UTF-8'); ?>" required>
    </div>

    <div class="form-group">
        <label for="gioiTinh">Giới tính:</label>
        <select id="gioiTinh" name="gioiTinh" class="form-control" required>
            <option value="Nam" <?php echo ($sinhvien->GioiTinh == 'Nam') ? 'selected' : ''; ?>>Nam</option>
            <option value="Nữ" <?php echo ($sinhvien->GioiTinh == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
        </select>
    </div>

    <div class="form-group">
        <label for="ngaySinh">Ngày sinh:</label>
        <input type="date" id="ngaySinh" name="ngaySinh" class="form-control"
               value="<?php echo date('Y-m-d', strtotime($sinhvien->NgaySinh)); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="hinh">Hình ảnh:</label>
        <?php 
        $image_path = 'uploads/sinhvien/' . $sinhvien->Hinh;
        $full_path = $_SERVER['DOCUMENT_ROOT'] . '/baitap/dangkyhocphan/' . $image_path;
        
        if (!empty($sinhvien->Hinh) && file_exists($full_path)): 
        ?>
            <div class="mb-2">
                <p>Hình ảnh hiện tại:</p>
                <img src="/baitap/dangkyhocphan/<?php echo $image_path; ?>" 
                     alt="<?php echo htmlspecialchars($sinhvien->HoTen, ENT_QUOTES, 'UTF-8'); ?>" 
                     style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
            </div>
        <?php else: ?>
            <div class="mb-2">
                <p>Hình ảnh hiện tại:</p>
                <img src="/baitap/dangkyhocphan/Content/images/sv1.jpg" 
                     alt="Default" 
                     style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
            </div>
        <?php endif; ?>
        
        <input type="file" id="hinh" name="hinh" class="form-control-file">
        <small class="form-text text-muted">Để trống nếu không muốn thay đổi hình ảnh. Chỉ chấp nhận file hình ảnh (JPG, JPEG, PNG, GIF)</small>
        
        <div id="imagePreview" class="mt-2" style="display: none;">
            <p>Xem trước hình mới:</p>
            <img id="previewImg" src="#" alt="Xem trước hình ảnh" style="max-width: 200px; max-height: 200px;" />
        </div>
    </div>

    <div class="form-group">
        <label for="maNganh">Ngành học:</label>
        <select id="maNganh" name="maNganh" class="form-control" required>
            <?php foreach ($nganhs as $nganh): ?>
                <option value="<?php echo $nganh->MaNganh; ?>" 
                        <?php echo ($sinhvien->MaNganh == $nganh->MaNganh) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($nganh->TenNganh, ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
</form>

<a href="/baitap/dangkyhocphan/SinhVien" class="btn btn-secondary mt-2">Quay lại danh sách sinh viên</a>

<script>
function validateForm() {
    var hoTen = document.getElementById('hoTen').value;
    var ngaySinh = document.getElementById('ngaySinh').value;
    
    if (hoTen.trim() === '') {
        alert('Họ tên không được để trống');
        return false;
    }
    
    if (ngaySinh.trim() === '') {
        alert('Ngày sinh không được để trống');
        return false;
    }
    
    return true;
}

// Xem trước hình ảnh khi chọn file
document.getElementById('hinh').addEventListener('change', function(e) {
    var preview = document.getElementById('previewImg');
    var previewDiv = document.getElementById('imagePreview');
    
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.style.display = 'block';
        }
        
        reader.readAsDataURL(this.files[0]);
    } else {
        previewDiv.style.display = 'none';
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>