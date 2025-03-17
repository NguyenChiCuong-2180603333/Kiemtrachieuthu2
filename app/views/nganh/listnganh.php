<?php include 'app/views/shares/header.php'; ?>

<h1>Danh mục ngành học</h1>
<a href="/baitap/dangkyhocphan/Nganh/add" class="btn btn-success mb-3">
    <i class="fas fa-plus"></i> Thêm ngành học mới
</a>

<?php if(empty($nganhs)): ?>
    <div class="alert alert-info">Không có ngành học nào</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Mã ngành</th>
                    <th>Tên ngành</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($nganhs as $nganh): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($nganh->MaNganh, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($nganh->TenNganh, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <a href="/baitap/dangkyhocphan/Nganh/edit/<?php echo $nganh->MaNganh; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <a href="/baitap/dangkyhocphan/Nganh/delete/<?php echo $nganh->MaNganh; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa ngành học này?');">
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