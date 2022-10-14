 <?php
 if (!defined('_INCODE')) die('Access Denied...');

 // Kiểm tra trạng thái đăng nhập
 if (!isLogin()) {
     redirect('admin?module=auth&action=login');
 } else {
     $userId = isLogin()['user_id'];
     $userDetail = getUserInfo($userId);
 }

 saveActivity(); // Lưu lại hoạt động cuối cùng của user

 autoRemoveTokenLogin();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $data['pageTitle']; ?> - Quản trị website</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets//css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/css/style.css?ver=<?php echo rand(); ?>">


    <script type="text/javascript"
            src="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/ckeditor/ckeditor.js">
    </script>

    <script type="text/javascript"
            src="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/ckfinder/ckfinder.js">
    </script>

    <?php
        head();
    ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
<!--    <div class="preloader flex-column justify-content-center align-items-center">-->
<!--        <img class="animation__shake" src="--><?php //echo _WEB_HOST_ADMIN_TEMPLATES; ?><!--/assets/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">-->
<!--    </div>-->

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i> Hi, <?php echo $userDetail['fullname']; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="<?php echo getLinkAdmin('users', 'profile'); ?>" class="dropdown-item">
                        <i class="fas fa-angle-right mr-2"></i>
                        Thông tin cá nhân
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo getLinkAdmin('users', 'change_password'); ?>" class="dropdown-item">
                        <i class="fas fa-angle-right mr-2"></i>
                        Đổi mật khẩu
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo getLinkAdmin('auth', 'logout'); ?>" class="dropdown-item">
                        <i class="fas fa-angle-right mr-2"></i>
                        Đăng xuất
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
