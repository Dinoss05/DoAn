<?php require 'views/admin/alerts.php'; ?>
<h3 class="mb-3">Quản lý Bài học</h3>
<?php require 'views/admin/nav.php'; ?>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold" id="lessonFormTitle">Thêm Bài học mới</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="lesson_id" id="lesson_id">
                    <div class="mb-3">
                        <label class="form-label">Thuộc khóa học</label>
                        <select name="course_id" id="lesson_course" class="form-select" required>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= $c['course_id'] ?>"><?= Validator::e($c['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên bài học (5-150 ký tự)</label>
                        <input type="text" name="title" id="lesson_title" class="form-control" required minlength="5" maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thứ tự hiển thị (>0)</label>
                        <input type="number" name="order_index" id="lesson_order" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội dung bài học (Hỗ trợ thẻ HTML)</label>
                        <textarea name="content" id="lesson_content" class="form-control" rows="6"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu bài học</button>
                    <button type="button" class="btn btn-secondary" onclick="resetLessonForm()">Hủy</button>
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
                            <th>TT</th>
                            <th>Tên bài học</th>
                            <th>Khóa học</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $stt = 1; ?>
                        <?php foreach ($lessons as $l): ?>
                            <tr>
                                <td><?= $stt++ ?></td>
                                <td class="fw-bold"><?= Validator::e($l['title']) ?></td>
                                <td><span class="badge bg-secondary"><?= Validator::e($l['course_title']) ?></span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick='editLesson(<?= json_encode($l, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Sửa</button>
                                    <a href="index.php?route=admin_lessons&delete=<?= $l['lesson_id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        data-info-title="Xóa bài học"
                                        data-info-confirm="Bạn có chắc muốn xóa bài học <?= Validator::e($l['title']) ?> không?">
                                        Xóa
                                    </a>
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
    function editLesson(l) {
        document.getElementById('lessonFormTitle').innerText = 'Cập nhật Bài học #' + l.lesson_id;
        document.getElementById('lesson_id').value = l.lesson_id;
        document.getElementById('lesson_course').value = l.course_id;
        document.getElementById('lesson_title').value = l.title;
        document.getElementById('lesson_order').value = l.order_index;
        document.getElementById('lesson_content').value = l.content;
    }

    function resetLessonForm() {
        document.getElementById('lessonFormTitle').innerText = 'Thêm Bài học mới';
        document.getElementById('lesson_id').value = '';
        document.getElementById('lesson_title').value = '';
        document.getElementById('lesson_order').value = 1;
        document.getElementById('lesson_content').value = '';
    }
</script>