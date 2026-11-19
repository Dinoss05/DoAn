<h3 class="mb-4">Khu vực Bài kiểm tra & Đánh giá năng lực</h3>

<div class="row g-4 mb-5">
    <!-- Đề tổng hợp ngẫu nhiên -->
    <div class="col-md-6">
        <div class="card border-primary shadow-sm h-100">
            <div class="card-body">
                <span class="badge bg-danger mb-2">15 Phút • 20 Câu hỏi</span>
                <h5 class="card-title">Đề Kiểm Tra Tổng Hợp Kiến Thức</h5>
                <p class="card-text text-muted">Tổng hợp ngẫu nhiên câu hỏi từ tất cả các khóa học (Từ vựng, Ngữ pháp, Đọc hiểu) có tính giờ làm bài.</p>
                <a href="index.php?route=take_exam&course_id=0" class="btn btn-primary fw-semibold">Bắt đầu làm bài &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Đề kiểm tra theo từng khóa học -->
    <?php foreach ($courses as $c): ?>
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <span class="badge bg-warning text-dark mb-2">10 Phút • Tối đa 15 Câu</span>
                <span class="badge bg-info text-dark mb-2"><?= Validator::e($c['level']) ?></span>
                <h5 class="card-title">Kiểm tra: <?= Validator::e($c['title']) ?></h5>
                <p class="card-text text-muted"><?= Validator::e($c['description']) ?></p>
                <a href="index.php?route=take_exam&course_id=<?= $c['course_id'] ?>" class="btn btn-outline-primary">Làm bài kiểm tra</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<h4 class="mb-3">Lịch sử làm bài gần đây của bạn</h4>
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Khóa học</th>
                    <th>Nội dung</th>
                    <th>Điểm số</th>
                    <th>Số câu</th>
                    <th>Thời gian nộp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($history)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Bạn chưa thực hiện bài kiểm tra nào.</td></tr>
                <?php else: ?>
                    <?php foreach ($history as $h): ?>
                    <tr>
                        <td><?= Validator::e($h['course_title']) ?></td>
                        <td class="fw-semibold"><?= Validator::e($h['lesson_title']) ?></td>
                        <td><span class="badge bg-<?= $h['score'] >= 5 ? 'success' : 'danger' ?> fs-6"><?= number_format($h['score'], 2) ?> / 10</span></td>
                        <td><?= $h['total_questions'] ?> câu</td>
                        <td><?= $h['completed_at'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>