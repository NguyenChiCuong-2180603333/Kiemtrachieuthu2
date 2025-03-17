<?php include 'app/views/shares/header.php'; ?>

<h1>Thêm sinh viên mới</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/baitap/dangkyhocphan/SinhVien/save" enctype="multipart/form-data" onsubmit="return validateForm();">
    <div class="form-group">
        <label for="maSV">Mã sinh viên:</label>
        <input type="text" id="maSV" name="maSV" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="hoTen">Họ tên:</label>
        <input type="text" id="hoTen" name="hoTen" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="gioiTinh">Giới tính:</label>
        <select id="gioiTinh" name="gioiTinh" class="form-control" required>
            <option value="Nam">Nam</option>
            <option value="Nữ">Nữ</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="ngaySinh">Ngày sinh:</label>
        <input type="date" id="ngaySinh" name="ngaySinh" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="hinh">Hình ảnh:</label>
        <input type="file" id="hinh" name="hinh" class="form-control-file">
        <small class="form-text text-muted">Chỉ chấp nhận file hình ảnh (JPG, JPEG, PNG, GIF)</small>
        
        <div id="imagePreview" class="mt-2" style="display: none;">
            <p>Xem trước:</p>
            <img id="previewImg" src="#" alt="Xem trước hình ảnh" style="max-width: 200px; max-height: 200px;" />
        </div>
    </div>
    
    <div class="form-group">
        <label for="maNganh">Ngành học:</label>
        <select id="maNganh" name="maNganh" class="form-control" required>
            <?php foreach ($nganhs as $nganh): ?>
                <option value="<?php echo htmlspecialchars($nganh->MaNganh, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($nganh->TenNganh, ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Thêm sinh viên</button>
</form>

<a href="/baitap/dangkyhocphan/SinhVien" class="btn btn-secondary mt-2">Quay lại danh sách sinh viên</a>

<script>
function validateForm() {
    var maSV = document.getElementById('maSV').value;
    var hoTen = document.getElementById('hoTen').value;
    var ngaySinh = document.getElementById('ngaySinh').value;
    
    if (maSV.trim() === '') {
        alert('Mã sinh viên không được để trống');
        return false;
    }
    
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