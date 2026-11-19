<?php require 'views/admin/alerts.php'; ?>
<h3 class="mb-3">Quản lý Khóa học</h3>
<?php require 'views/admin/nav.php'; ?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold" id="courseFormTitle">Thêm Khóa học mới</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="course_id" id="course_id">
                    <div class="mb-3">
                        <label class="form-label">Tên khóa học (5-150 ký tự)</label>
                        <input type="text" name="title" id="course_title" class="form-control" required minlength="5" maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trình độ</label>
                        <select name="level" id="course_level" class="form-select" required>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả khóa học</label>
                        <textarea name="description" id="course_desc" class="form-control" rows="3" maxlength="500"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu khóa học</button>
                    <button type="button" class="btn btn-secondary" onclick="resetCourseForm()">Hủy</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên khóa học</th>
                            <th>Trình độ</th>
                            <th>Số bài</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $c): ?>
                            <tr>
                                <td><?= $c['course_id'] ?></td>
                                <td>
                                    <div class="fw-bold"><?= Validator::e($c['title']) ?></div>
                                    <small class="text-muted"><?= Validator::e($c['description']) ?></small>
                                </td>
                                <td><span class="badge bg-info text-dark"><?= Validator::e($c['level']) ?></span></td>
                                <td><?= $c['total_lessons'] ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick='editCourse(<?= json_encode($c) ?>)'>Sửa</button>
                                    <a href="index.php?route=admin_courses&delete=<?= $c['course_id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        data-info-title="Xóa khóa học"
                                        data-info-confirm="Xóa khóa học này sẽ xóa các bài học liên quan. Bạn có muốn tiếp tục?">
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
    function editCourse(c) {
        document.getElementById('courseFormTitle').innerText = 'Cập nhật Khóa học #' + c.course_id;
        document.getElementById('course_id').value = c.course_id;
        document.getElementById('course_title').value = c.title;
        document.getElementById('course_level').value = c.level;
        document.getElementById('course_desc').value = c.description;
    }

    function resetCourseForm() {
        document.getElementById('courseFormTitle').innerText = 'Thêm Khóa học mới';
        document.getElementById('course_id').value = '';
        document.getElementById('course_title').value = '';
        document.getElementById('course_level').value = 'Beginner';
        document.getElementById('course_desc').value = '';
    }
</script>