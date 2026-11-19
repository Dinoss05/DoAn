<?php
session_start();
require_once 'core/Validator.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/CourseController.php';
require_once 'controllers/AdminController.php';

$route = $_GET['route'] ?? 'home';

$auth = new AuthController();
$course = new CourseController();
$admin = new AdminController();

switch ($route) {
    case 'register': $auth->register(); break;
    case 'login':    $auth->login(); break;
    case 'logout':   $auth->logout(); break;
    case 'profile':  $auth->profile(); break;

    case 'home':     $course->home(); break;
    case 'course':   $course->detail(); break;
    case 'lesson':   $course->lesson(); break;
    case 'quiz':     $course->quiz(); break;
    case 'exams':     $course->exams(); break;
    case 'take_exam': $course->takeExam(); break;

    case 'admin':           $admin->dashboard(); break;
    case 'admin_users':     $admin->users(); break;
    case 'admin_courses':   $admin->courses(); break;
    case 'admin_lessons':   $admin->lessons(); break;
    case 'admin_questions': $admin->questions(); break;

    default: $course->home(); break;
}