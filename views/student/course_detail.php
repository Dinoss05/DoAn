<div class="mb-3">
    <a href="index.php?route=home" class="btn btn-outline-secondary btn-sm">&larr; Quay lại danh sách khóa học</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <span class="badge bg-info text-dark mb-2"><?= Validator::e($course['level']) ?></span>
        <h3><?= Validator::e($course['title']) ?></h3>
        <p class="text-muted mb-0"><?= Validator::e($course['description']) ?></p>
    </div>
</div>

<h5 class="mb-3">Danh sách bài học</h5>
<div class="list-group shadow-sm">
    <?php if (empty($lessons)): ?>
        <div class="list-group-item text-muted">Khóa học này chưa có bài học nào.</div>
    <?php else: ?>
        <?php foreach ($lessons as $l): ?>
            <?php
                $statusBadge = '<span class="badge bg-secondary">Chưa học</span>';
                if (($l['user_status'] ?? '') === 'in_progress') {
                    $statusBadge = '<span class="badge bg-warning text-dark">Đang học</span>';
                } elseif (($l['user_status'] ?? '') === 'completed') {
                    $statusBadge = '<span class="badge bg-success">Hoàn thành</span>';
                }
            ?>
            <a href="index.php?route=lesson&id=<?= $l['lesson_id'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                <div>
                    <span class="fw-bold me-2">Bài <?= $l['order_index'] ?>:</span>
                    <?= Validator::e($l['title']) ?>
                </div>
                <div><?= $statusBadge ?></div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>