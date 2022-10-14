<?php
if (!defined('_INCODE')) die('Access Denied...');
$data = [
    'pageTitle' => 'Cập nhật thông tin cá nhân'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$userId = isLogin()['user_id'];
$userDetail = getUserInfo($userId);

setFlashData('userDetail', $userDetail);
// Xử lý cập nhật thông tin cá nhân
if (isPost()) {
    // Validate form
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = []; // Mảng lưu trữ các lỗi

    // Validate Họ tên: Bắt buộc nhập, => 5 ký tự
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
    } else {
        if (strlen(trim($body['fullname']))<5) {
            $errors['fullname']['min'] = 'Họ tên phải lớn hơn hoặc bằng 5 ký tự';
        }
    }

    // Validate email: Bắt buộc phải nhập, Định dạng email, Email phải duy nhất
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        if (!isEmail(trim($body['email']))) {
            $errors['email']['isEmail'] = 'Email không hợp lệ';
        } else {
            // Kiểm tra email có tồn tại trong Database
            $email =trim($body['email']);
            $sql = "SELECT id FROM users WHERE email='$email' AND id<>$userId";
            if (getRows($sql) > 0) {
                $errors['email']['unique'] = 'Địa chỉ email đã tồn tại';
            }
        }
    }

    // Kiểm tra mảng $errors
    if (empty($errors)) {
        // Không có lỗi xảy ra
        $dataUpdate = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'contact_facebook' => $body['contact_facebook'],
            'contact_twitter' => $body['contact_twitter'],
            'contact_linkedin' => $body['contact_linkedin'],
            'contact_pinterest' => $body['contact_pinterest'],
            'about_content' => $body['about_content'],
            'update_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=$userId";

        $updateStatus = update('users', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật thông tin thành công');
            setFlashData('msg_type', 'success');

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

    redirect('admin?module=users&action=profile');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');
$userDetail = getFlashData('userDetail');
if (!empty($userDetail) && empty($old)) {
    $old = $userDetail;
}
?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php
                getMsg($msg, $msg_type);
            ?>
            <form action="" method="post">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Họ và tên</label>
                            <input type="text" class="form-control" name="fullname" placeholder="Họ và tên..."
                                   value="<?php echo old('fullname', $old); ?>">
                            <?php echo form_error('fullname', $errors,
                                '<span class="error">', '</span>') ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Email..."
                                   value="<?php echo old('email', $old); ?>">
                            <?php echo form_error('email', $errors,
                                '<span class="error">', '</span>') ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Facebook</label>
                            <input type="text" class="form-control" name="contact_facebook" placeholder="Facebook..."
                                   value="<?php echo old('contact_facebook', $old); ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Twitter</label>
                            <input type="text" class="form-control" name="contact_twitter" placeholder="Twitter..."
                                   value="<?php echo old('contact_twitter', $old); ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Linkedin</label>
                            <input type="text" class="form-control" name="contact_linkedin" placeholder="Linkedin..."
                                   value="<?php echo old('contact_linkedin', $old); ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Pinterest</label>
                            <input type="text" class="form-control" name="contact_pinterest" placeholder="Pinterest..."
                                   value="<?php echo old('contact_pinterest', $old); ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">Nội dung giới thiệu</label>
                            <textarea name="about_content" class="form-control"
                                      placeholder="Nội dung giới thiệu..."
                            ><?php echo old('about_content', $old); ?></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?php echo getLinkAdmin(''); ?>"
                   class="btn btn-success" style="margin-left: 10px">Quay lại</a>
            </form>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

<?php
layout('footer', 'admin', $data);
