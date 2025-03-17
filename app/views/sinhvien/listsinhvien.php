<?php include 'app/views/shares/header.php'; ?>
<h1>Danh sách sinh viên</h1>
<a href="/baitap/dangkyhocphan/SinhVien/add" class="btn btn-success mb-2">Thêm sinh viên mới</a>
<?php if(empty($sinhviens)): ?>
    <div class="alert alert-info">Không có sinh viên nào</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Mã SV</th>
                    <th>Hình</th>
                    <th>Họ tên</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Ngành học</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($sinhviens as $sv): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sv->MaSV, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <?php if (!empty($sv->Hinh)): ?>
                                <img src="/baitap/dangkyhocphan/uploads/sinhvien/<?php echo htmlspecialchars($sv->Hinh, ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="<?php echo htmlspecialchars($sv->HoTen, ENT_QUOTES, 'UTF-8'); ?>" class="img-thumbnail" width="50">
                            <?php else: ?>
                                <img src="/baitap/dangkyhocphan/Content/images/sv1.jpg" alt="Default" class="img-thumbnail" width="50">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($sv->HoTen, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($sv->GioiTinh, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($sv->NgaySinh)); ?></td>
                        <td><?php echo htmlspecialchars($sv->TenNganh, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <a href="/baitap/dangkyhocphan/SinhVien/show/<?php echo $sv->MaSV; ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-info-circle"></i> Chi tiết
                            </a>
                            <a href="/baitap/dangkyhocphan/SinhVien/edit/<?php echo $sv->MaSV; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <a href="/baitap/dangkyhocphan/SinhVien/delete/<?php echo $sv->MaSV; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php include 'app/views/shares/footer.php'; ?>