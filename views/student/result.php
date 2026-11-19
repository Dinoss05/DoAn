<div class="card border-success mb-4 shadow-sm">
    <div class="card-body text-center">
        <h3>Kết quả kiểm tra: <?= Validator::e($lesson['title']) ?></h3>
        <h1 class="display-4 text-success fw-bold"><?= number_format($score, 2) ?> / 10</h1>
        <p class="lead">Bạn trả lời đúng <strong><?= $correctCount ?> / <?= $total ?></strong> câu hỏi.</p>
        <a href="index.php?route=course&id=<?= $lesson['course_id'] ?>" class="btn btn-primary">Quay lại khóa học</a>
    </div>
</div>

<?php foreach ($details as $idx => $item): ?>
<div class="card mb-3 border-<?= $item['is_correct'] ? 'success' : 'danger' ?>">
    <div class="card-body">
        <p class="fw-bold">Câu <?= $idx + 1 ?>: <?= Validator::e($item['question']['question_text']) ?></p>
        <p class="mb-1">Bạn chọn: <span class="badge bg-<?= $item['is_correct'] ? 'success' : 'danger' ?>"><?= $item['user_answer'] ?? 'Bỏ trống' ?></span> | Đáp án đúng: <span class="badge bg-success"><?= $item['question']['correct_option'] ?></span></p>
        <?php if (!empty($item['question']['explanation'])): ?>
            <div class="alert alert-light border mt-2 mb-0 small">
                <strong>Giải thích:</strong> <?= Validator::e($item['question']['explanation']) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>