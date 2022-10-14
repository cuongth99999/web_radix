<?php
if (!defined('_INCODE')) die('Access Denied...');
$data = [
    'pageTitle' => 'Đổi mật khẩu'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$userId = isLogin()['user_id'];
$userDetail = getUserInfo($userId);

setFlashData('userDetail', $userDetail);
// Xử lý đổi mật khẩu
if (isPost()) {
    // Validate form
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = []; // Mảng lưu trữ các lỗi

    // Kiểm tra trùng khớp mật khẩu cũ
    if (empty(trim($body['old_password']))) {
        $errors['old_password']['required'] = 'Vui lòng nhập mật khẩu cũ';
    } else {
        $oldPassword = trim($body['old_password']);
        $hashPassword = $userDetail['password'];
        if (!password_verify($oldPassword, $hashPassword)) {
            $errors['old_password']['match'] = 'Mật khẩu cũ không chính xác';
        }
    }

    // Validate password: Bắt buộc phải nhập, >= 8 ký tự
    if (empty(trim($body['password']))) {
        $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
    } else {
        if (strlen(trim($body['password']))<8) {
            $errors['password']['min'] = 'Mật khẩu không được nhỏ hơn 8 ký tự';
        }
    }

    // Validate confirm password: Bắt buộc phải nhập, phải giống trường với password
    if (empty(trim($body['confirm_password']))) {
        $errors['confirm_password']['required'] = 'Xác nhận mật khẩu không được để trống';
    } else {
        if ($body['password'] != trim($body['confirm_password'])) {
            $errors['confirm_password']['match'] = 'Mật khẩu nhập lại không trùng khớp';
        }
    }

    // Kiểm tra mảng $errors
    if (empty($errors)) {
        // Không có lỗi xảy ra
        $dataUpdate = [
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'update_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=$userId";

        $updateStatus = update('users', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Đổi mật khẩu thành công! Bạn có thể đăng nhập ngay bây giờ.');
            setFlashData('msg_type', 'success');

            // Gửi mail thông báo khi đổi mật khẩu thành công
            $subject = 'Đổi mật khẩu thành công';
            $content = 'Chúc mừng bạn đã đổi mật khẩu thành công!';
            sendMail($userDetail['email'], $subject, $content);

            // Đăng xuất
            redirect('admin?module=auth&action=logout');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        // Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('erros', $errors);
        setFlashData('old', $body);
    }

    redirect('admin?module=users&action=change_password');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');

?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php
            getMsg($msg, $msg_type);
            ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="">Mật khẩu cũ</label>
                    <input type="password" class="form-control" name="old_password" placeholder="Mật khẩu cũ...">
                    <?php echo form_error('old_password', $errors,
                        '<span class="error">', '</span>') ?>
                </div>
                <div class="form-group">
                    <label for="">Mật khẩu mới</label>
                    <input type="password" class="form-control" name="password" placeholder="Mật khẩu mới...">
                    <?php echo form_error('password', $errors,
                        '<span class="error">', '</span>') ?>
                </div>
                <div class="form-group">
                    <label for="">Nhập lại mật khẩu mới</label>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Nhập lại mật khẩu mới...">
                    <?php echo form_error('confirm_password', $errors,
                        '<span class="error">', '</span>') ?>
                </div>

                <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                <a href="<?php echo getLinkAdmin(''); ?>"
                   class="btn btn-success" style="margin-left: 10px">Quay lại</a>
            </form>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

<?php
layout('footer', 'admin', $data);
