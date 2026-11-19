<h3 class="mb-4">Thông tin tài khoản cá nhân</h3>

<?php if (!empty($msg)): ?>
    <div class="alert alert-success"><?= Validator::e($msg) ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $err): ?>
                <li><?= Validator::e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Form 1: Cập nhật thông tin -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Cập nhật thông tin cơ bản</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="update_info">
                    <div class="mb-3">
                        <label class="form-label">Tên tài khoản (Không thể thay đổi)</label>
                        <input type="text" class="form-control bg-light" value="<?= Validator::e($_SESSION['user']['username']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" name="full_name" class="form-control" required
                               value="<?= Validator::e($_SESSION['user']['full_name']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Form 2: Đổi mật khẩu -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Đổi mật khẩu</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="change_password">
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="confirm_new_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Cập nhật mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>