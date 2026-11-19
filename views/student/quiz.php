<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="badge bg-<?= $isTimed ? 'danger' : 'info text-dark' ?> mb-1">
            <?= $isTimed ? 'BÀI KIỂM TRA TÍNH GIỜ' : 'BÀI TẬP LUYỆN TẬP' ?>
        </span>
        <h4 class="mb-0"><?= Validator::e($quizTitle) ?></h4>
    </div>

    <?php if ($isTimed): ?>
        <div class="alert alert-warning mb-0 py-2 px-3 fw-bold">
            Thời gian còn lại: <span id="timer">10:00</span>
        </div>
    <?php endif; ?>
</div>

<?php
// Gom câu hỏi theo từng bài học để hiển thị đoạn văn/ngữ cảnh đi kèm
$groupedQuestions = [];
foreach ($questions as $q) {
    $lid = $q['lesson_id'];
    if (!isset($groupedQuestions[$lid])) {
        $groupedQuestions[$lid] = [
            'lesson_title' => $q['lesson_title'] ?? '',
            'lesson_content' => $q['lesson_content'] ?? '',
            'items' => []
        ];
    }
    $groupedQuestions[$lid]['items'][] = $q;
}
$stt = 1;
?>

<form id="quizForm" method="POST">
    <?php foreach ($groupedQuestions as $group): ?>
        <?php if (!empty($group['lesson_content'])): ?>
            <div class="card border-info mb-3 shadow-sm">
                <div class="card-header bg-info bg-opacity-10 fw-bold">
                    Ngữ cảnh / Đoạn văn: <?= Validator::e($group['lesson_title']) ?>
                </div>
                <div class="card-body">
                    <?= $group['lesson_content'] ?>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($group['items'] as $q): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <p class="fw-bold">Câu <?= $stt++ ?>: <?= Validator::e($q['question_text']) ?></p>
                    <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
                        <?php $col = 'option_' . strtolower($opt); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[<?= $q['question_id'] ?>]" value="<?= $opt ?>" id="q<?= $q['question_id'].$opt ?>">
                            <label class="form-check-label" for="q<?= $q['question_id'].$opt ?>">
                                <strong><?= $opt ?>.</strong> <?= Validator::e($q[$col]) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-success btn-lg">Nộp bài & Xem kết quả</button>
</form>

<?php if ($isTimed): ?>
<script>
let seconds = <?= (int)$durationSeconds ?>;
const timerEl = document.getElementById('timer');
const countdown = setInterval(() => {
    seconds--;
    let m = String(Math.floor(seconds / 60)).padStart(2, '0');
    let s = String(seconds % 60).padStart(2, '0');
    timerEl.textContent = `${m}:${s}`;
    if (seconds <= 0) {
        clearInterval(countdown);
        alert("Hết thời gian làm bài! Hệ thống tự động nộp bài.");
        document.getElementById('quizForm').submit();
    }
}, 1000);
</script>
<?php endif; ?>