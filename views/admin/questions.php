<?php require 'views/admin/alerts.php'; ?>
<h3 class="mb-3">Quản lý Ngân hàng Câu hỏi</h3>
<?php require 'views/admin/nav.php'; ?>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold" id="qFormTitle">Thêm Câu hỏi mới</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="question_id" id="question_id">
                    <div class="mb-2">
                        <label class="form-label">Bài học liên kết</label>
                        <select name="lesson_id" id="q_lesson" class="form-select" required>
                            <?php foreach ($lessons as $l): ?>
                                <option value="<?= $l['lesson_id'] ?>"><?= Validator::e($l['course_title'] . ' - ' . $l['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nội dung câu hỏi</label>
                        <textarea name="question_text" id="q_text" class="form-control" rows="2" maxlength="1000" required></textarea>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="text" name="option_a" id="q_a" class="form-control" placeholder="Đáp án A" maxlength="255" required>
                        </div>
                        <div class="col-6">
                            <input type="text" name="option_b" id="q_b" class="form-control" placeholder="Đáp án B" maxlength="255" required>
                        </div>
                        <div class="col-6">
                            <input type="text" name="option_c" id="q_c" class="form-control" placeholder="Đáp án C" maxlength="255" required>
                        </div>
                        <div class="col-6">
                            <input type="text" name="option_d" id="q_d" class="form-control" placeholder="Đáp án D" maxlength="255" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Đáp án đúng</label>
                        <select name="correct_option" id="q_correct" class="form-select" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giải thích đáp án</label>
                        <textarea name="explanation" id="q_exp" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu câu hỏi</button>
                    <button type="button" class="btn btn-secondary" onclick="resetQForm()">Hủy</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Câu hỏi</th>
                            <th>Bài học</th>
                            <th>Đúng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $q): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= Validator::e($q['question_text']) ?></div>
                                <small class="text-muted">
                                    A: <?= Validator::e($q['option_a']) ?> | B: <?= Validator::e($q['option_b']) ?> |
                                    C: <?= Validator::e($q['option_c']) ?> | D: <?= Validator::e($q['option_d']) ?>
                                </small>
                            </td>
                            <td><span class="badge bg-secondary"><?= Validator::e($q['lesson_title']) ?></span></td>
                            <td><span class="badge bg-success"><?= $q['correct_option'] ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick='editQuestion(<?= json_encode($q) ?>)'>Sửa</button>
                                <a href="index.php?route=admin_questions&delete=<?= $q['question_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa câu hỏi này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function editQuestion(q) {
    document.getElementById('qFormTitle').innerText = 'Cập nhật Câu hỏi #' + q.question_id;
    document.getElementById('question_id').value = q.question_id;
    document.getElementById('q_lesson').value = q.lesson_id;
    document.getElementById('q_text').value = q.question_text;
    document.getElementById('q_a').value = q.option_a;
    document.getElementById('q_b').value = q.option_b;
    document.getElementById('q_c').value = q.option_c;
    document.getElementById('q_d').value = q.option_d;
    document.getElementById('q_correct').value = q.correct_option;
    document.getElementById('q_exp').value = q.explanation;
}
function resetQForm() {
    document.getElementById('qFormTitle').innerText = 'Thêm Câu hỏi mới';
    document.getElementById('question_id').value = '';
    document.getElementById('q_text').value = '';
    document.getElementById('q_a').value = '';
    document.getElementById('q_b').value = '';
    document.getElementById('q_c').value = '';
    document.getElementById('q_d').value = '';
    document.getElementById('q_correct').value = 'A';
    document.getElementById('q_exp').value = '';
}
</script>