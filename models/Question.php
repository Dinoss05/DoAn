<?php
require_once 'config/database.php';

class Question {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByLesson($lessonId) {
        $stmt = $this->db->prepare("SELECT * FROM questions WHERE lesson_id = ? ORDER BY question_id ASC");
        $stmt->execute([$lessonId]);
        return $stmt->fetchAll();
    }

    public function getAll() {
        return $this->db->query("SELECT q.*, l.title AS lesson_title FROM questions q JOIN lessons l ON q.lesson_id = l.lesson_id ORDER BY q.question_id DESC")->fetchAll();
    }

    public function save($lessonId, $text, $a, $b, $c, $d, $correct, $explanation, $id = null) {
        if ($id) {
            $stmt = $this->db->prepare("UPDATE questions SET lesson_id=?, question_text=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=?, explanation=? WHERE question_id=?");
            return $stmt->execute([$lessonId, $text, $a, $b, $c, $d, $correct, $explanation, $id]);
        }
        $stmt = $this->db->prepare("INSERT INTO questions (lesson_id, question_text, option_a, option_b, option_c, option_d, correct_option, explanation) VALUES (?,?,?,?,?,?,?,?)");
        return $stmt->execute([$lessonId, $text, $a, $b, $c, $d, $correct, $explanation]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM questions WHERE question_id = ?");
        return $stmt->execute([$id]);
    }

    public function saveTestResult($userId, $lessonId, $score, $total) {
        $stmt = $this->db->prepare("INSERT INTO test_results (user_id, lesson_id, score, total_questions) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$userId, $lessonId, $score, $total]);
    }

    public function getStats() {
        $students = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
        $tests = $this->db->query("SELECT COUNT(*) FROM test_results")->fetchColumn();
        $avgScore = $this->db->query("SELECT AVG(score) FROM test_results")->fetchColumn();
        return [
            'total_students' => (int)$students,
            'total_tests' => (int)$tests,
            'avg_score' => $avgScore ? round($avgScore, 2) : 0
        ];
    }
    // Lấy câu hỏi theo bài học cụ thể (kèm đoạn văn / nội dung bài học)
    public function getExamByLesson($lessonId) {
        $stmt = $this->db->prepare("
            SELECT q.*, l.title AS lesson_title, l.content AS lesson_content
            FROM questions q
            JOIN lessons l ON q.lesson_id = l.lesson_id
            WHERE q.lesson_id = ?
            ORDER BY q.question_id ASC
        ");
        $stmt->execute([(int)$lessonId]);
        return $stmt->fetchAll();
    }

    // Lấy câu hỏi theo khóa học (kèm đoạn văn của từng bài để không bị mất ngữ cảnh)
    public function getByCourse($courseId, $limit = 15) {
        $stmt = $this->db->prepare("
            SELECT q.*, l.title AS lesson_title, l.content AS lesson_content
            FROM questions q
            JOIN lessons l ON q.lesson_id = l.lesson_id
            WHERE l.course_id = ?
            ORDER BY q.lesson_id ASC, q.question_id ASC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$courseId, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy đề kiểm tra tổng hợp (nhóm theo bài học để giữ đủ đoạn văn/lý thuyết)
    public function getRandomAll($limit = 20) {
        $stmt = $this->db->prepare("
            SELECT q.*, l.title AS lesson_title, l.content AS lesson_content
            FROM questions q
            JOIN lessons l ON q.lesson_id = l.lesson_id
            ORDER BY q.lesson_id ASC, RAND()
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy lịch sử làm bài kiểm tra của học viên
    public function getUserHistory($userId) {
        $stmt = $this->db->prepare("
            SELECT tr.*, l.title AS lesson_title, c.title AS course_title
            FROM test_results tr
            JOIN lessons l ON tr.lesson_id = l.lesson_id
            JOIN courses c ON l.course_id = c.course_id
            WHERE tr.user_id = ?
            ORDER BY tr.completed_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}