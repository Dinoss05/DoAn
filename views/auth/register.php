<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Đăng ký tài khoản Học viên</h4>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?= Validator::e($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên tài khoản (Username)</label>
                        <input type="text" name="username" class="form-control" required
                               pattern="^[a-zA-Z0-9_]{4,30}$"
                               title="4-30 ký tự, chỉ gồm chữ không dấu, số và dấu gạch dưới (_)"
                               value="<?= Validator::e($_POST['username'] ?? '') ?>">
                        <div class="form-text">4–30 ký tự (chữ không dấu, số, dấu gạch dưới _).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ và tên</label>
                        <input type="text" name="full_name" class="form-control" required
                               minlength="2" maxlength="50"
                               value="<?= Validator::e($_POST['full_name'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                        <div class="form-text">Tối thiểu 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt (@$!%*?&).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Xác nhận mật khẩu</label>
                        <input type="password" name="confirm_password" class="form-control" required minlength="8">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                </form>
                <div class="text-center mt-3">
                    Đã có tài khoản? <a href="index.php?route=login">Đăng nhập ngay</a>
                </div>
            </div>
        </div>
    </div>
</div>