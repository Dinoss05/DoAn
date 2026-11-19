<?php
class Validator {
    public static function validateUsername($username) {
        return preg_match('/^[a-zA-Z0-9_]{4,30}$/', $username);
    }

    public static function validateFullName($fullName) {
        $trimmed = trim($fullName);
        if (mb_strlen($trimmed, 'UTF-8') < 2 || mb_strlen($trimmed, 'UTF-8') > 50) {
            return false;
        }
        return preg_match('/^[\p{L}\s]+$/u', $trimmed);
    }

    public static function validatePassword($password) {
        if (preg_match('/\s/', $password)) return false;
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
    }

    // Làm sạch chống XSS khi xuất ra HTML
    public static function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}