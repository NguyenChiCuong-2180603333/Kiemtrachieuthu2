<?php include 'app/views/shares/header.php'; ?>

<div class="container">
    <h1>Giỏ đăng ký học phần</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
    <div class="mb-3">
        <a href="<?php echo '/baitap/dangkyhocphan/HocPhan'; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách học phần
        </a>
    </div>
    
    <?php if(empty($_SESSION['cart'])): ?>
        <div class="alert alert-info">Giỏ đăng ký trống</div>
    <?php else: ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Danh sách học phần đã chọn</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mã HP</th>
                                <th>Tên học phần</th>
                                <th>Số tín chỉ</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalCredits = 0;
                            foreach($_SESSION['cart'] as $hp): 
                                $totalCredits += $hp['SoTinChi'];
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($hp['MaHP'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($hp['TenHP'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($hp['SoTinChi'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <a href="<?php echo '/baitap/dangkyhocphan/HocPhan/remove?id=' . $hp['MaHP']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa học phần này khỏi giỏ đăng ký?');">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Tổng số tín chỉ:</th>
                                <th><?php echo $totalCredits; ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="<?php echo '/baitap/dangkyhocphan/HocPhan/clear'; ?>" 
                       class="btn btn-warning"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả học phần khỏi giỏ đăng ký?');">
                        <i class="fas fa-trash"></i> Xóa đăng ký
                    </a>
                    <a href="<?php echo '/baitap/dangkyhocphan/HocPhan/save'; ?>" 
                       class="btn btn-success"
                       onclick="return confirm('Bạn có chắc chắn muốn lưu đăng ký học phần?');">
                        <i class="fas fa-save"></i> Lưu đăng ký
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>