<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Đăng nhập hệ thống</h4>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success"><?= Validator::e($_SESSION['flash_success']) ?></div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= Validator::e($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên tài khoản</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                </form>
                <div class="text-center mt-3">
                    Chưa có tài khoản? <a href="index.php?route=register">Đăng ký miễn phí</a>
                </div>
            </div>
        </div>
    </div>
</div>