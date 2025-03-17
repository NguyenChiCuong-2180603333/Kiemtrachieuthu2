<?php include 'app/views/shares/header.php'; ?>

<div class="container">
    <h1>Danh sách học phần</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="<?php echo '/baitap/dangkyhocphan/HocPhan/cart'; ?>" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Xem giỏ đăng ký
                <?php 
                $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                if ($cartCount > 0): 
                ?>
                <span class="badge badge-light"><?php echo $cartCount; ?></span>
                <?php endif; ?>
            </a>
        </div>
    </div>
    
    <?php if(empty($hocphans)): ?>
        <div class="alert alert-info">Không có học phần nào</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Mã HP</th>
                        <th>Tên học phần</th>
                        <th>Số tín chỉ</th>
                        <th>Số lượng dự kiến</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($hocphans as $hp): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($hp->MaHP, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($hp->TenHP, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($hp->SoTinChi, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php if(isset($hp->SoLuongDuKien)): ?>
                                    <?php echo htmlspecialchars($hp->SoLuongDuKien, ENT_QUOTES, 'UTF-8'); ?>
                                <?php else: ?>
                                    Không giới hạn
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $inCart = isset($_SESSION['cart'][$hp->MaHP]);
                                if (!$inCart && (!isset($hp->SoLuongDuKien) || $hp->SoLuongDuKien > 0)): 
                                ?>
                                <a href="<?php echo '/baitap/dangkyhocphan/HocPhan/add?id=' . $hp->MaHP; ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Đăng ký
                                </a>
                                <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <?php echo $inCart ? 'Đã đăng ký' : 'Hết chỗ'; ?>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>