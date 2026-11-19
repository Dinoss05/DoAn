<?php $isPractice = (($mode ?? 'practice') === 'practice'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="badge bg-<?= $isPractice ? 'info text-dark' : 'danger' ?> mb-1">
            <?= $isPractice ? 'BÀI LUYỆN TẬP • CỦNG CỐ KIẾN THỨC' : 'BÀI KIỂM TRA • ÁP DỤNG & ĐÁNH GIÁ' ?>
        </span>
        <h4 class="mb-0"><?= Validator::e($quizTitle) ?></h4>
    </div>

    <?php if ($isTimed): ?>
        <div class="alert alert-warning mb-0 py-2 px-3 fw-bold">
            Thời gian còn lại: <span id="timer"><?= gmdate('i:s', (int)$durationSeconds) ?></span>
        </div>
    <?php endif; ?>
</div>

<?php if ($isPractice): ?>
    <div class="alert alert-info shadow-sm mb-4">
        <div class="fw-bold mb-1">Hướng dẫn luyện tập:</div>
        <ul class="mb-0 small">
            <li>Đọc kỹ <strong>Từ vựng trọng tâm</strong> gắn với từng câu hỏi để nhận diện ngữ cảnh và cấu trúc.</li>
            <li>Nếu chưa chắc chắn, bấm <strong>Xem gợi ý</strong> để định hướng cách làm trước khi chọn đáp án.</li>
            <li>Hoàn thành bài luyện tập để ghi nhận tiến độ bài học và chuẩn bị làm <strong>Bài kiểm tra</strong> áp dụng.</li>
        </ul>
    </div>
<?php endif; ?>

<?php
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
        <?php if (!$isPractice && !empty($group['lesson_content'])): ?>
            <div class="card border-primary mb-3 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 fw-bold">
                    Ngữ cảnh / Tình huống áp dụng: <?= Validator::e($group['lesson_title']) ?>
                </div>
                <div class="card-body">
                    <?= $group['lesson_content'] ?>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($group['items'] as $q): ?>
            <?php $qid = (int)$q['question_id']; ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <p class="fw-bold mb-2">Câu <?= $stt++ ?>: <?= Validator::e($q['question_text']) ?></p>

                    <?php if ($isPractice && !empty($q['vocabulary'])): ?>
                        <div class="mb-2">
                            <span class="badge bg-light text-dark border">
                                Từ vựng trọng tâm: <strong class="text-primary"><?= Validator::e($q['vocabulary']) ?></strong>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
                        <?php $col = 'option_' . strtolower($opt); ?>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="answers[<?= $qid ?>]" value="<?= $opt ?>" id="q<?= $qid . $opt ?>">
                            <label class="form-check-label" for="q<?= $qid . $opt ?>">
                                <strong><?= $opt ?>.</strong> <?= Validator::e($q[$col]) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($isPractice && !empty($q['hint'])): ?>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleHint(<?= $qid ?>)">
                                Xem gợi ý làm bài
                            </button>
                            <div id="hint-box-<?= $qid ?>" class="alert alert-warning py-2 px-3 mt-2 mb-0 small d-none">
                                <strong>Gợi ý:</strong> <?= Validator::e($q['hint']) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-success btn-lg">
        <?= $isPractice ? 'Nộp bài luyện tập & Xem giải thích' : 'Nộp bài kiểm tra & Xem điểm' ?>
    </button>
</form>

<?php if ($isPractice): ?>
<script>
function toggleHint(qid) {
    const box = document.getElementById('hint-box-' + qid);
    if (box) {
        box.classList.toggle('d-none');
    }
}
</script>
<?php endif; ?>

<?php if ($isTimed): ?>
<script>
let seconds = <?= (int)$durationSeconds ?>;
const timerEl = document.getElementById('timer');
const countdown = setInterval(() => {
    seconds--;
    if (seconds < 0) seconds = 0;
    let m = String(Math.floor(seconds / 60)).padStart(2, '0');
    let s = String(seconds % 60).padStart(2, '0');
    timerEl.textContent = `${m}:${s}`;
    if (seconds <= 0) {
        clearInterval(countdown);
        alert("Hết thời gian làm bài kiểm tra! Hệ thống tự động nộp bài.");
        document.getElementById('quizForm').submit();
    }
}, 1000);
</script>
<?php endif; ?>