<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File dùng để cập nhật thông tin nhóm người dùng
 * */
$data = [
    'pageTitle' => 'Cập nhật người dùng',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Truy vấn lấy danh sách nhóm
$alLGroups = getRaw("SELECT id, name FROM groups ORDER BY name");

// Lấy dữ liệu cũ của người dùng
$body = getBody('get');

if (!empty($body['id'])) {
    $userId = $body['id'];

    // Kiểm tra $userId có tồn tại trong database hay không?
    // Nếu tồn tại => lấy ra thông tin
    // Nếu không tồn tại => Chuyển hướng về trang lists
    $userDetail = firstRaw("SELECT * FROM users WHERE id=$userId");
    if (!empty($userDetail)) {
        // Tồn tại
        // Gán giá trị $userDetail vào flashData
        setFlashData('userDetail', $userDetail);

    } else {
        redirect('admin?module=users');
    }
} else {
    redirect('admin?module=users');
}

// Xử lý sửa người dùng
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

    // Validate nhóm người dùng: Bắt buộc phải chọn nhóm
    if (empty(trim($body['group_id']))) {
        $errors['group_id']['required'] = 'Vui lòng chọn nhóm người dùng';
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

    // Validate confirm password: Bắt buộc phải nhập, phải giống trường với password
    if (!empty(trim($body['password']))) {
        // Chỉ validate confirm_password nếu password được nhập
        if (empty(trim($body['confirm_password']))) {
            $errors['confirm_password']['required'] = 'Xác nhận mật khẩu không được để trống';
        } else {
            if ($body['password'] != trim($body['confirm_password'])) {
                $errors['confirm_password']['match'] = 'Mật khẩu nhập lại không trùng khớp';
            }
        }
    }

    // Kiểm tra mảng $errors
    if (empty($errors)) {
        // Không có lỗi xảy ra
        $dataUpdate = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'group_id' => $body['group_id'],
            'status' => $body['status'],
            'update_at' => date('Y-m-d H:i:s')
        ];

        if (!empty(trim($body['password']))) {
            $dataUpdate['password'] = password_hash($body['password'], PASSWORD_DEFAULT);
        }
        $condition = "id=$userId";
        $updateStatus = update('users', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật thông tin người dùng thành công');
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

    redirect('admin?module=users&action=edit&id='.$userId);
}

// Xử lý sửa người dùng

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');
$userDetail = getFlashData('userDetail');

if (empty($old) && !empty($userDetail)) {
    $old = $userDetail;
}
/* Trường hợp 1: Khi load trang, vẫn muốn sử dụng biến old => gán $old = $userDetail
 * Trường hợp 2: Khi submit form => Validate bị lỗi => muốn dữ lại các dữ liệu vừa nhập
 *  */
?>
    <!-- Main content -->
    <section class="content">
        <?php
        getMsg($msg, $msg_type);
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="">Họ và tên</label>
                        <input type="text" name="fullname" class="form-control" placeholder="Họ và tên..."
                               value="<?php echo old('fullname', $old); ?>">
                        <?php echo form_error('fullname', $errors,
                            '<span class="error">', '</span>') ?>
                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Địa chỉ email..."
                               value="<?php echo old('email', $old); ?>">
                        <?php echo form_error('email', $errors,
                            '<span class="error">', '</span>') ?>
                    </div>

                    <div class="form-group">
                        <label for="">Nhóm người dùng</label>
                        <select name="group_id" class="form-control">
                            <option value="0">Chọn nhóm</option>
                            <?php
                            if (!empty($alLGroups)) {
                                foreach ($alLGroups as $item) {
                                    ?>
                                    <option value="<?php echo $item['id']; ?>"
                                        <?php echo (old('group_id', $old)==$item['id'])?'selected':false;
                                        ?>><?php echo $item['name']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <?php echo form_error('group_id', $errors,
                            '<span class="error">', '</span>') ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">Mật khẩu</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Mật khẩu (Không nhập nếu không thay đổi)...">
                        <?php echo form_error('password', $errors,
                            '<span class="error">', '</span>') ?>
                    </div>

                    <div class="form-group">
                        <label for="">Nhập lại mật khẩu</label>
                        <input type="password" name="confirm_password" class="form-control"
                               placeholder="Nhập lại mật khẩu (Không nhập nếu không thay đổi)...">
                        <?php echo form_error('confirm_password', $errors,
                            '<span class="error">', '</span>') ?>
                    </div>

                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="0" <?php echo (old('status', $old)==0)?'selected':false; ?>>
                                Chưa kích hoạt</option>
                            <option value="1" <?php echo (old('status', $old)==1)?'selected':false; ?>>
                                Đã kích hoạt</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo getLinkAdmin('users', 'lists'); ?>"
               class="btn btn-success" style="margin-left: 10px">Quay lại</a>
        </form>
    </section>
<?php
layout('footer', 'admin', $data);