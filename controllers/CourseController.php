<?php
require_once 'core/Controller.php';
require_once 'models/Course.php';
require_once 'models/Lesson.php';
require_once 'models/Question.php';

class CourseController extends Controller {
    private $courseModel;
    private $lessonModel;
    private $questionModel;

    public function __construct() {
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->questionModel = new Question();
    }

    public function home() {
        $userId = $_SESSION['user']['user_id'] ?? null;
        $courses = $this->courseModel->getAllWithProgress($userId);
        $this->render('student/home', ['courses' => $courses]);
    }

    public function detail() {
        $this->requireLogin();
        $courseId = (int)($_GET['id'] ?? 0);
        $course = $this->courseModel->findById($courseId);
        if (!$course) die("Khóa học không tồn tại.");

        $lessons = $this->lessonModel->getByCourse($courseId, $_SESSION['user']['user_id']);
        $this->render('student/course_detail', ['course' => $course, 'lessons' => $lessons]);
    }

    public function lesson() {
        $this->requireLogin();
        $lessonId = (int)($_GET['id'] ?? 0);
        $lesson = $this->lessonModel->findById($lessonId);
        if (!$lesson) die("Bài học không tồn tại.");

        $this->lessonModel->markProgress($_SESSION['user']['user_id'], $lessonId, 'in_progress');
        $questions = $this->questionModel->getByLesson($lessonId);
        $this->render('student/lesson_detail', ['lesson' => $lesson, 'hasQuiz' => count($questions) > 0]);
    }

    // Luyện tập cuối bài học (Không đếm giờ)
    public function quiz() {
        $this->requireLogin();
        $lessonId = (int)($_GET['id'] ?? 0);
        $lesson = $this->lessonModel->findById($lessonId);
        $questions = $this->questionModel->getByLesson($lessonId);
        if (!$lesson || empty($questions)) die("Bài tập không khả dụng.");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->gradeAndRender($questions, $lesson['title'], $lessonId, $lesson['course_id'], true);
            return;
        }

        $this->render('student/quiz', [
            'quizTitle' => 'Luyện tập: ' . $lesson['title'],
            'questions' => $questions,
            'isTimed' => false
        ]);
    }

    // Trang danh sách Đề kiểm tra & Lịch sử điểm
    public function exams() {
        $this->requireLogin();
        $courses = $this->courseModel->getAllWithProgress();
        $history = $this->questionModel->getUserHistory($_SESSION['user']['user_id']);
        $this->render('student/exams', ['courses' => $courses, 'history' => $history]);
    }

    // Làm bài kiểm tra tính giờ (Có đồng hồ đếm ngược)
    public function takeExam() {
        $this->requireLogin();
        $courseId = (int)($_GET['course_id'] ?? 0);

        if ($courseId > 0) {
            $course = $this->courseModel->findById($courseId);
            if (!$course) die("Đề kiểm tra không tồn tại.");
            $questions = $this->questionModel->getByCourse($courseId, 15);
            $title = "Kiểm tra tổng hợp: " . $course['title'];
            $duration = 600; // 10 phút
        } else {
            $questions = $this->questionModel->getRandomAll(20);
            $title = "Đề kiểm tra tổng hợp kiến thức chung (20 câu)";
            $duration = 900; // 15 phút
        }

        if (empty($questions)) die("Chưa có đủ câu hỏi cho bài kiểm tra này.");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $examQuestions = $_SESSION['current_exam_questions'] ?? $questions;
            $firstLessonId = $examQuestions[0]['lesson_id'];
            unset($_SESSION['current_exam_questions']);
            $this->gradeAndRender($examQuestions, $title, $firstLessonId, $courseId, false);
            return;
        }

        $_SESSION['current_exam_questions'] = $questions;
        $this->render('student/quiz', [
            'quizTitle' => $title,
            'questions' => $questions,
            'isTimed' => true,
            'durationSeconds' => $duration
        ]);
    }

    // Hàm dùng chung chấm điểm & xuất kết quả
    private function gradeAndRender($questions, $title, $lessonId, $courseId, $markLessonCompleted) {
        $selected = $_POST['answers'] ?? [];
        $total = count($questions);
        $correctCount = 0;
        $details = [];

        foreach ($questions as $q) {
            $qid = $q['question_id'];
            $userAns = $selected[$qid] ?? null;
            $isCorrect = ($userAns === $q['correct_option']);
            if ($isCorrect) $correctCount++;

            $details[] = [
                'question' => $q,
                'user_answer' => $userAns,
                'is_correct' => $isCorrect
            ];
        }

        $score = round(($correctCount / $total) * 10, 2);
        $userId = $_SESSION['user']['user_id'];

        $this->questionModel->saveTestResult($userId, $lessonId, $score, $total);
        if ($markLessonCompleted) {
            $this->lessonModel->markProgress($userId, $lessonId, 'completed');
        }

        $this->render('student/result', [
            'lesson' => ['title' => $title, 'course_id' => $courseId],
            'score' => $score,
            'correctCount' => $correctCount,
            'total' => $total,
            'details' => $details
        ]);
    }
}