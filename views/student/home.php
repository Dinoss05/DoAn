<h3 class="mb-4">Danh sách Khóa học Tiếng Anh</h3>
<div class="row g-4">
    <?php foreach ($courses as $c): ?>
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <span class="badge bg-info text-dark mb-2"><?= Validator::e($c['level']) ?></span>
                <h5 class="card-title"><?= Validator::e($c['title']) ?></h5>
                <p class="card-text text-muted"><?= Validator::e($c['description']) ?></p>
                
                <?php if (isset($_SESSION['user'])): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Tiến độ hoàn thành</span>
                        <span><?= $c['completed_lessons'] ?>/<?= $c['total_lessons'] ?> bài (<?= $c['progress_percent'] ?>%)</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: <?= $c['progress_percent'] ?>%"></div>
                    </div>
                </div>
                <?php endif; ?>
                <a href="index.php?route=course&id=<?= $c['course_id'] ?>" class="btn btn-primary">Vào học ngay</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>