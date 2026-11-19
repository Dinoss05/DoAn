<div class="mb-3">
    <a href="index.php?route=course&id=<?= $lesson['course_id'] ?>" class="btn btn-outline-secondary btn-sm">&larr; Quay lại khóa học</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h3 class="mb-0"><?= Validator::e($lesson['title']) ?></h3>
    </div>
    <div class="card-body fs-6 lh-lg">
        <!-- Nội dung bài học hỗ trợ định dạng HTML từ Admin CMS -->
        <?= $lesson['content'] ?>
    </div>
    <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
        <span class="text-muted small">Hoàn thành bài kiểm tra bên dưới để ghi nhận tiến độ bài học.</span>
        <?php if ($hasQuiz): ?>
            <a href="index.php?route=quiz&id=<?= $lesson['lesson_id'] ?>" class="btn btn-primary fw-bold">Làm bài tập trắc nghiệm &rarr;</a>
        <?php else: ?>
            <span class="badge bg-secondary">Chưa có bài tập cho bài học này</span>
        <?php endif; ?>
    </div>
</div>