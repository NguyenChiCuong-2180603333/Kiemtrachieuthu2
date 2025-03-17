<?php include 'app/views/shares/header.php'; ?>

<h1>Sửa ngành học</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/baitap/dangkyhocphan/Nganh/update">
    <input type="hidden" name="maNganh" value="<?php echo $nganh->MaNganh; ?>">

    <div class="form-group">
        <label for="tenNganh">Tên ngành:</label>
        <input type="text" id="tenNganh" name="tenNganh" class="form-control" 
               value="<?php echo htmlspecialchars($nganh->TenNganh, ENT_QUOTES, 'UTF-8'); ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
</form>

<a href="/baitap/dangkyhocphan/Nganh" class="btn btn-secondary mt-2">Quay lại danh sách ngành học</a>

<?php include 'app/views/shares/footer.php'; ?>