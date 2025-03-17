<?php include 'app/views/shares/header.php'; ?>

<h1>Thêm ngành học mới</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/baitap/dangkyhocphan/Nganh/save">
    <div class="form-group">
        <label for="maNganh">Mã ngành:</label>
        <input type="text" id="maNganh" name="maNganh" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="tenNganh">Tên ngành:</label>
        <input type="text" id="tenNganh" name="tenNganh" class="form-control" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Thêm ngành học</button>
</form>

<a href="/baitap/dangkyhocphan/Nganh" class="btn btn-secondary mt-2">Quay lại danh sách ngành học</a>

<?php include 'app/views/shares/footer.php'; ?>