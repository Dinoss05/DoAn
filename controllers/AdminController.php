<?php
require_once 'core/Controller.php';
require_once 'models/User.php';
require_once 'models/Course.php';
require_once 'models/Lesson.php';
require_once 'models/Question.php';

class AdminController extends Controller {
    public function dashboard() {
        $this->requireAdmin();
        $qModel = new Question();
        $stats = $qModel->getStats();
        $this->render('admin/dashboard', ['stats' => $stats]);
    }

    public function users() {
        $this->requireAdmin();
        $userModel = new User();

        if (isset($_GET['toggle_id']) && isset($_GET['status'])) {
            $toggleId = (int)$_GET['toggle_id'];
            if ($toggleId > 0 && $toggleId !== (int)$_SESSION['user']['user_id']) {
                $newStatus = $_GET['status'] === 'active' ? 'locked' : 'active';
                $userModel->toggleStatus($toggleId, $newStatus);
                $_SESSION['flash_success'] = ($newStatus === 'locked')
                    ? "Đã khóa tài khoản thành công!"
                    : "Đã mở khóa tài khoản thành công!";
            }
            $this->redirect('admin_users');
        }

        if (isset($_GET['delete_id'])) {
            $deleteId = (int)$_GET['delete_id'];
            if ($deleteId > 0 && $deleteId !== (int)$_SESSION['user']['user_id']) {
                $userModel->delete($deleteId);
                $_SESSION['flash_success'] = "Đã xóa vĩnh viễn tài khoản và toàn bộ dữ liệu liên quan!";
            } else {
                $_SESSION['flash_error'] = "Không thể xóa tài khoản đang đăng nhập!";
            }
            $this->redirect('admin_users');
        }

        $this->render('admin/users', ['users' => $userModel->getAll()]);
    }

    public function courses() {
        $this->requireAdmin();
        $cModel = new Course();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $level = $_POST['level'] ?? 'Beginner';
            $id = !empty($_POST['course_id']) ? (int)$_POST['course_id'] : null;

            if (mb_strlen($title, 'UTF-8') >= 5 && mb_strlen($title, 'UTF-8') <= 150 && in_array($level, ['Beginner', 'Intermediate', 'Advanced'])) {
                $cModel->save($title, $desc, $level, $id);
                $_SESSION['flash_success'] = $id ? "Cập nhật khóa học thành công!" : "Thêm khóa học mới thành công!";
            } else {
                $_SESSION['flash_error'] = "Dữ liệu khóa học không hợp lệ (tên khóa học từ 5–150 ký tự).";
            }
            $this->redirect('admin_courses');
        }

        if (isset($_GET['delete'])) {
            $cModel->delete((int)$_GET['delete']);
            $_SESSION['flash_success'] = "Đã xóa khóa học thành công!";
            $this->redirect('admin_courses');
        }

        $this->render('admin/courses', ['courses' => $cModel->getAllWithProgress()]);
    }

    public function lessons() {
        $this->requireAdmin();
        $lModel = new Lesson();
        $cModel = new Course();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseId = (int)($_POST['course_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $content = $_POST['content'] ?? '';
            $order = (int)($_POST['order_index'] ?? 0);
            $id = !empty($_POST['lesson_id']) ? (int)$_POST['lesson_id'] : null;

            if ($courseId > 0 && mb_strlen($title, 'UTF-8') >= 5 && mb_strlen($title, 'UTF-8') <= 150 && $order > 0) {
                $lModel->save($courseId, $title, $content, $order, $id);
                $_SESSION['flash_success'] = $id ? "Cập nhật bài học thành công!" : "Thêm bài học mới thành công!";
            } else {
                $_SESSION['flash_error'] = "Dữ liệu bài học không hợp lệ (tên 5–150 ký tự, thứ tự > 0).";
            }
            $this->redirect('admin_lessons');
        }

        if (isset($_GET['delete'])) {
            $lModel->delete((int)$_GET['delete']);
            $_SESSION['flash_success'] = "Đã xóa bài học thành công!";
            $this->redirect('admin_lessons');
        }

        $this->render('admin/lessons', ['lessons' => $lModel->getAll(), 'courses' => $cModel->getAllWithProgress()]);
    }

    public function questions() {
        $this->requireAdmin();
        $qModel = new Question();
        $lModel = new Lesson();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lessonId = (int)($_POST['lesson_id'] ?? 0);
            $text = trim($_POST['question_text'] ?? '');
            $a = trim($_POST['option_a'] ?? '');
            $b = trim($_POST['option_b'] ?? '');
            $c = trim($_POST['option_c'] ?? '');
            $d = trim($_POST['option_d'] ?? '');
            $correct = $_POST['correct_option'] ?? '';
            $exp = trim($_POST['explanation'] ?? '');
            $id = !empty($_POST['question_id']) ? (int)$_POST['question_id'] : null;

            if ($lessonId > 0 && $text && $a && $b && $c && $d && in_array($correct, ['A', 'B', 'C', 'D'])) {
                $qModel->save($lessonId, $text, $a, $b, $c, $d, $correct, $exp, $id);
                $_SESSION['flash_success'] = $id ? "Cập nhật câu hỏi thành công!" : "Thêm câu hỏi mới thành công!";
            } else {
                $_SESSION['flash_error'] = "Vui lòng nhập đầy đủ nội dung câu hỏi, 4 đáp án A/B/C/D và chọn đáp án đúng.";
            }
            $this->redirect('admin_questions');
        }

        if (isset($_GET['delete'])) {
            $qModel->delete((int)$_GET['delete']);
            $_SESSION['flash_success'] = "Đã xóa câu hỏi thành công!";
            $this->redirect('admin_questions');
        }

        $this->render('admin/questions', ['questions' => $qModel->getAll(), 'lessons' => $lModel->getAll()]);
    }
}