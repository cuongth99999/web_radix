<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File dùng để cập nhật thông tin trang
 * */
$data = [
    'pageTitle' => 'Cập nhật đăng ký',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Lấy dữ liệu cũ
$body = getBody('get');

if (!empty($body['id'])) {
    $subscribeId = $body['id'];

    // Kiểm tra $pageId có tồn tại trong database hay không?
    // Nếu tồn tại => lấy ra thông tin
    // Nếu không tồn tại => Chuyển hướng về trang lists
    $subscribeDetail = firstRaw("SELECT * FROM subscribe WHERE id=$subscribeId");
    if (!empty($subscribeDetail)) {
        // Tồn tại
        // Gán giá trị $subscribeDetail vào flashData
        setFlashData('subscribeDetail', $subscribeDetail);

    } else {
        redirect('admin?module=subscribe');
    }
} else {
    redirect('admin?module=subscribe');
}

// Xử lý sửa nhóm người dùng
if (isPost()) {
    // Validate form
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = []; // Mảng lưu trữ các lỗi

    // Validate name
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Tên không được để trống';
    } else {
        if (strlen(trim($body['fullname']))<5) {
            $errors['fullname']['min'] = 'Tên phải lớn hơn hoặc bằng 5 ký tự';
        }
    }

    // Validate email: Bắt buộc phải nhập, Định dạng email, Email phải duy nhất
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        if (!isEmail(trim($body['email']))) {
            $errors['email']['isEmail'] = 'Email không hợp lệ';
        }
    }

    // Kiểm tra mảng $errors
    if (empty($errors)) {
        // Không có lỗi xảy ra
        $dataUpdate = [
            'fullname' => trim(strip_tags($body['fullname'])),
            'email' => trim(strip_tags($body['email'])),
            'status' => trim($body['status']),
            'update_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=$subscribeId";
        $updateStatus = update('subscribe', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật đăng ký thành công');
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

    redirect('admin?module=subscribe&action=edit&id='.$subscribeId);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');
$subscribeDetail = getFlashData('subscribeDetail');
if (empty($old) && !empty($subscribeDetail)) {
    $old = $subscribeDetail;
}
?>
    <!-- Main content -->
    <section class="content">
        <form action="" method="post">
            <div class="form-group">
                <?php
                getMsg($msg, $msg_type);
                ?>
                <div class="form-group">
                    <label for="">Họ và tên</label>
                    <input type="text" class="form-control" name="fullname" placeholder="Họ và tên..." value="<?php echo old('fullname', $old); ?>"/>
                    <?php echo form_error('fullname', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Email..." value="<?php echo old('email', $old); ?>"/>
                    <?php echo form_error('email', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="0" <?php echo old('status', $old)==0?'selected':false; ?>>Chưa xử lý</option>
                        <option value="1" <?php echo old('status', $old)==1?'selected':false; ?>>Đã xử lý</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo getLinkAdmin('subscribe', 'lists'); ?>"
               class="btn btn-success" style="margin-left: 10px">Quay lại</a>
        </form>
    </section>
<?php
layout('footer', 'admin', $data);
