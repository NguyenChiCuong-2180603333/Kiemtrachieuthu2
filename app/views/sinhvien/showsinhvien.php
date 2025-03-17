<?php include 'app/views/shares/header.php'; ?>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <?php 
            $image_path = 'uploads/sinhvien/' . $sinhvien->Hinh;
            $full_path = $_SERVER['DOCUMENT_ROOT'] . '/baitap/dangkyhocphan/' . $image_path;
            
            if (!empty($sinhvien->Hinh) && file_exists($full_path)): 
            ?>
                <img src="/baitap/dangkyhocphan/<?php echo $image_path; ?>" 
                     class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($sinhvien->HoTen, ENT_QUOTES, 'UTF-8'); ?>"
                     style="max-height: 300px; object-fit: contain; padding: 15px;">
            <?php else: ?>
                <img src="/baitap/dangkyhocphan/Content/images/sv1.jpg" alt="Default" class="card-img-top img-fluid"
                     style="max-height: 300px; object-fit: contain; padding: 15px;">
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-8">
        <h1><?php echo htmlspecialchars($sinhvien->HoTen, ENT_QUOTES, 'UTF-8'); ?></h1>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Mã sinh viên:</strong> <?php echo htmlspecialchars($sinhvien->MaSV, ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Giới tính:</strong> <?php echo htmlspecialchars($sinhvien->GioiTinh, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Ngày sinh:</strong> <?php echo date('d/m/Y', strtotime($sinhvien->NgaySinh)); ?></p>
                <p><strong>Ngành học:</strong> <?php echo htmlspecialchars($sinhvien->TenNganh ?? 'Không có ngành', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
        
        <div class="mb-3 mt-4">
            <div class="btn-group">
                <a href="/baitap/dangkyhocphan/SinhVien/edit/<?php echo $sinhvien->MaSV; ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Sửa thông tin
                </a>
                <a href="/baitap/dangkyhocphan/SinhVien/delete/<?php echo $sinhvien->MaSV; ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">
                    <i class="fas fa-trash"></i> Xóa sinh viên
                </a>
            </div>
        </div>
        
        <a href="/baitap/dangkyhocphan/SinhVien" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>