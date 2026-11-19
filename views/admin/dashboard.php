<h3 class="mb-3">Bảng điều khiển Quản trị (CMS)</h3>
<?php require 'views/admin/nav.php'; ?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Tổng số học viên</h6>
                <h2 class="display-5 fw-bold mb-0"><?= $stats['total_students'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Lượt làm bài kiểm tra</h6>
                <h2 class="display-5 fw-bold mb-0"><?= $stats['total_tests'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-dark bg-warning shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Điểm trung bình toàn hệ thống</h6>
                <h2 class="display-5 fw-bold mb-0"><?= number_format($stats['avg_score'], 2) ?> / 10</h2>
            </div>
        </div>
    </div>
</div>