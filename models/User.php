<?php
require_once 'config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($username, $fullName, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, full_name, password_hash) VALUES (?, ?, ?)");
        return $stmt->execute([$username, trim($fullName), $hash]);
    }

    public function updateProfile($userId, $fullName) {
        $stmt = $this->db->prepare("UPDATE users SET full_name = ? WHERE user_id = ?");
        return $stmt->execute([trim($fullName), $userId]);
    }

    public function updatePassword($userId, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        return $stmt->execute([$hash, $userId]);
    }

    // Cho Admin
    public function getAll() {
        return $this->db->query("SELECT user_id, username, full_name, role, status, created_at FROM users ORDER BY created_at DESC")->fetchAll();
    }

    public function toggleStatus($userId, $status) {
        $stmt = $this->db->prepare("UPDATE users SET status = ? WHERE user_id = ?");
        return $stmt->execute([$status, $userId]);
    }
    public function delete($userId) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
}