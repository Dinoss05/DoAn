<?php
require_once 'core/Controller.php';
require_once 'core/Validator.php';
require_once 'models/User.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['full_name'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

            if (!Validator::validateUsername($username)) {
                $errors[] = "Tên tài khoản phải từ 4-30 ký tự, chỉ gồm chữ không dấu, số và dấu gạch dưới (_).";
            } elseif ($this->userModel->findByUsername($username)) {
                $errors[] = "Tên tài khoản đã tồn tại trên hệ thống.";
            }

            if (!Validator::validateFullName($fullName)) {
                $errors[] = "Họ và tên phải từ 2-50 ký tự, không chứa số hoặc ký tự đặc biệt.";
            }

            if (!Validator::validatePassword($password)) {
                $errors[] = "Mật khẩu tối thiểu 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt (@$!%*?&), không khoảng trắng.";
            }

            if ($password !== $confirm) {
                $errors[] = "Xác nhận mật khẩu không trùng khớp.";
            }

            if (empty($errors)) {
                $this->userModel->create($username, $fullName, $password);
                $_SESSION['flash_success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                $this->redirect('login');
            }
        }
        $this->render('auth/register', ['errors' => $errors]);
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByUsername($username);
            if ($user && password_verify($password, $user['password_hash'])) {
                if ($user['status'] === 'locked') {
                    $error = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Quản trị viên.";
                } else {
                    $_SESSION['user'] = [
                        'user_id' => $user['user_id'],
                        'username' => $user['username'],
                        'full_name' => $user['full_name'],
                        'role' => $user['role']
                    ];
                    $this->redirect($user['role'] === 'admin' ? 'admin' : 'home');
                }
            } else {
                $error = "Tên tài khoản hoặc mật khẩu không chính xác.";
            }
        }
        $this->render('auth/login', ['error' => $error]);
    }

    public function profile() {
        $this->requireLogin();
        $userId = $_SESSION['user']['user_id'];
        $msg = '';
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'update_info') {
                $fullName = $_POST['full_name'] ?? '';
                if (!Validator::validateFullName($fullName)) {
                    $errors[] = "Họ tên không hợp lệ (2-50 ký tự, chỉ gồm chữ cái và khoảng trắng).";
                } else {
                    $this->userModel->updateProfile($userId, $fullName);
                    $_SESSION['user']['full_name'] = trim($fullName);
                    $msg = "Cập nhật họ tên thành công!";
                }
            } elseif ($action === 'change_password') {
                $current = $_POST['current_password'] ?? '';
                $newPass = $_POST['new_password'] ?? '';
                $confirm = $_POST['confirm_new_password'] ?? '';
                $userDb = $this->userModel->findById($userId);

                if (!password_verify($current, $userDb['password_hash'])) {
                    $errors[] = "Mật khẩu hiện tại không chính xác.";
                } elseif ($current === $newPass) {
                    $errors[] = "Mật khẩu mới phải khác mật khẩu hiện tại.";
                } elseif (!Validator::validatePassword($newPass)) {
                    $errors[] = "Mật khẩu mới tối thiểu 8 ký tự, gồm chữ hoa, thường, số và ký tự đặc biệt (@$!%*?&).";
                } elseif ($newPass !== $confirm) {
                    $errors[] = "Xác nhận mật khẩu mới không trùng khớp.";
                } else {
                    $this->userModel->updatePassword($userId, $newPass);
                    $msg = "Đổi mật khẩu thành công!";
                }
            }
        }
        $this->render('auth/profile', ['msg' => $msg, 'errors' => $errors]);
    }

    public function logout() {
        session_destroy();
        $this->redirect('login');
    }
}