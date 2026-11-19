<?php
require_once 'config/database.php';

class Lesson {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByCourse($courseId, $userId = null) {
        $stmt = $this->db->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY lesson_id");
        $stmt->execute([$courseId]);
        $lessons = $stmt->fetchAll();

        if ($userId) {
            foreach ($lessons as &$lesson) {
                $st = $this->db->prepare("SELECT status FROM user_progress WHERE user_id = ? AND lesson_id = ?");
                $st->execute([$userId, $lesson['lesson_id']]);
                $lesson['user_status'] = $st->fetchColumn() ?: 'not_started';
            }
        }
        return $lessons;
    }

    public function getAll() {
        return $this->db->query("SELECT l.*, c.title AS course_title FROM lessons l JOIN courses c ON l.course_id = c.course_id ORDER BY c.course_id, l.order_index")->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM lessons WHERE lesson_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function markProgress($userId, $lessonId, $status) {
        $stmt = $this->db->prepare("
            INSERT INTO user_progress (user_id, lesson_id, status)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE status = IF(status = 'completed', 'completed', VALUES(status))
        ");
        return $stmt->execute([$userId, $lessonId, $status]);
    }

    public function save($courseId, $title, $content, $orderIndex, $id = null) {
        if ($id) {
            $stmt = $this->db->prepare("UPDATE lessons SET course_id = ?, title = ?, content = ?, order_index = ? WHERE lesson_id = ?");
            return $stmt->execute([$courseId, $title, $content, $orderIndex, $id]);
        }
        $stmt = $this->db->prepare("INSERT INTO lessons (course_id, title, content, order_index) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$courseId, $title, $content, $orderIndex]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM lessons WHERE lesson_id = ?");
        return $stmt->execute([$id]);
    }
}