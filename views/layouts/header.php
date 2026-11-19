<!-- views/layouts/header.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Học Tập Tiếng Anh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .user-badge-box {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background-color: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 999px;
            padding: 4px 14px 4px 5px;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .user-badge-box:hover {
            background-color: rgba(255, 255, 255, 0.24);
            border-color: rgba(255, 255, 255, 0.65);
            color: #fff;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            background-color: #fff;
            flex-shrink: 0;
        }

        .user-meta {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
            text-align: left;
        }

        .user-fullname {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .user-username {
            font-size: 0.72rem;
            opacity: 0.85;
        }
    </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?route=home">STU English</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php?route=home">Khóa học</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?route=exams">Bài kiểm tra</a></li>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link text-warning fw-bold" href="index.php?route=admin">Quản trị CMS</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav align-items-lg-center gap-2 mt-2 mt-lg-0">
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php
                        $fullName = Validator::e($_SESSION['user']['full_name']);
                        $username = Validator::e($_SESSION['user']['username']);
                        // Ảnh mặc định tạo theo tên (không cần tải file ảnh), hoặc đổi thành đường dẫn ảnh local như 'assets/img/avatar.png'
                        $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($_SESSION['user']['full_name']) . "&background=0d6efd&color=fff&bold=true";
                        ?>
                        <li class="nav-item">
                            <a href="index.php?route=profile" class="user-badge-box" title="Xem hồ sơ cá nhân">
                                <img src="<?= $avatarUrl ?>" alt="Avatar của <?= $fullName ?>" class="user-avatar">
                                <span class="user-meta">
                                    <span class="user-fullname"><?= $fullName ?></span>
                                    <span class="user-username">@<?= $username ?></span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-sm btn-danger text-white px-3" href="index.php?route=logout">Đăng xuất</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?route=login">Đăng nhập</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?route=register">Đăng ký</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-4 flex-grow-1">