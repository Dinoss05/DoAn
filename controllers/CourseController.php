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
        $practiceQuestions = $this->questionModel->getPracticeByLesson($lessonId);
        $this->render('student/lesson_detail', [
            'lesson' => $lesson,
            'hasQuiz' => count($practiceQuestions) > 0
        ]);
    }

    // Bài luyện tập cuối bài học (Có hướng dẫn, từ vựng, gợi ý; không đếm giờ)
    public function quiz() {
        $this->requireLogin();
        $lessonId = (int)($_GET['id'] ?? 0);
        $lesson = $this->lessonModel->findById($lessonId);
        $questions = $this->questionModel->getPracticeByLesson($lessonId);
        if (!$lesson || empty($questions)) die("Bài luyện tập không khả dụng cho bài học này.");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->gradeAndRender($questions, 'Luyện tập: ' . $lesson['title'], $lessonId, $lesson['course_id'], true);
            return;
        }

        $this->render('student/quiz', [
            'quizTitle' => 'Luyện tập: ' . $lesson['title'],
            'lesson' => $lesson,
            'questions' => $questions,
            'mode' => 'practice',
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

    // Bài kiểm tra đánh giá & áp dụng kiến thức (Có đồng hồ đếm ngược, không hiện gợi ý/từ vựng hỗ trợ)
    public function takeExam() {
        $this->requireLogin();
        $courseId = (int)($_GET['course_id'] ?? 0);

        if ($courseId > 0) {
            $course = $this->courseModel->findById($courseId);
            if (!$course) die("Đề kiểm tra không tồn tại.");
            $questions = $this->questionModel->getExamByCourse($courseId, 15);
            $title = "Bài kiểm tra áp dụng: " . $course['title'];
            $duration = 600; // 10 phút
        } else {
            $questions = $this->questionModel->getExamRandomAll(20);
            $title = "Bài kiểm tra tổng hợp kiến thức (20 câu)";
            $duration = 900; // 15 phút
        }

        if (empty($questions)) die("Chưa có đủ câu hỏi kiểm tra cho mục này.");

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
            'mode' => 'exam',
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