<h3 class="mb-3">Quản lý Tài khoản Người dùng</h3>
<?php require 'views/admin/nav.php'; ?>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= Validator::e($_SESSION['flash_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= Validator::e($_SESSION['flash_error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên tài khoản</th>
                    <th>Họ và tên</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['user_id'] ?></td>
                        <td class="fw-bold"><?= Validator::e($u['username']) ?></td>
                        <td><?= Validator::e($u['full_name']) ?></td>
                        <td><span class="badge bg-<?= $u['role'] === 'admin' ? 'dark' : 'info text-dark' ?>"><?= $u['role'] ?></span></td>
                        <td>
                            <span class="badge bg-<?= $u['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= $u['status'] === 'active' ? 'Hoạt động' : 'Đã khóa' ?>
                            </span>
                        </td>
                        <td><?= $u['created_at'] ?></td>
                        <td>
                            <?php if ((int)$u['user_id'] !== (int)$_SESSION['user']['user_id']): ?>
                                <div class="d-flex gap-1">
                                    <a href="index.php?route=admin_users&toggle_id=<?= $u['user_id'] ?>&status=<?= $u['status'] ?>"
                                        class="btn btn-sm btn-<?= $u['status'] === 'active' ? 'outline-warning' : 'outline-success' ?>"
                                        data-info-title="Trạng thái tài khoản"
                                        data-info-confirm="Xác nhận thay đổi trạng thái tài khoản <?= Validator::e($u['username']) ?>?">
                                        <?= $u['status'] === 'active' ? 'Khóa tài khoản' : 'Mở khóa' ?>
                                    </a>
                                    <a href="index.php?route=admin_users&delete_id=<?= $u['user_id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        data-info-title="Xóa người dùng"
                                        data-info-confirm="Xóa tài khoản <?= Validator::e($u['username']) ?> cùng dữ liệu học tập liên quan?">
                                        Xóa
                                    </a>
                                </div>
                            <?php else: ?>
                                <span class="text-muted small">Đang đăng nhập</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>