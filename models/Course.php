<?php
require_once 'config/database.php';

class Course {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllWithProgress($userId = null) {
        $courses = $this->db->query("SELECT * FROM courses ORDER BY course_id ASC")->fetchAll();
        foreach ($courses as &$course) {
            $stmtTotal = $this->db->prepare("SELECT COUNT(*) FROM lessons WHERE course_id = ?");
            $stmtTotal->execute([$course['course_id']]);
            $total = (int)$stmtTotal->fetchColumn();

            $completed = 0;
            if ($userId && $total > 0) {
                $stmtDone = $this->db->prepare("
                    SELECT COUNT(*) FROM user_progress up
                    JOIN lessons l ON up.lesson_id = l.lesson_id
                    WHERE up.user_id = ? AND l.course_id = ? AND up.status = 'completed'
                ");
                $stmtDone->execute([$userId, $course['course_id']]);
                $completed = (int)$stmtDone->fetchColumn();
            }
            $course['total_lessons'] = $total;
            $course['completed_lessons'] = $completed;
            $course['progress_percent'] = $total > 0 ? round(($completed / $total) * 100) : 0;
        }
        return $courses;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE course_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function save($title, $description, $level, $id = null) {
        if ($id) {
            $stmt = $this->db->prepare("UPDATE courses SET title = ?, description = ?, level = ? WHERE course_id = ?");
            return $stmt->execute([$title, $description, $level, $id]);
        }
        $stmt = $this->db->prepare("INSERT INTO courses (title, description, level) VALUES (?, ?, ?)");
        return $stmt->execute([$title, $description, $level]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE course_id = ?");
        return $stmt->execute([$id]);
    }
}