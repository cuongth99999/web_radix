<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File dùng để cập nhật thông tin nhóm người dùng
 * */
$data = [
    'pageTitle' => 'Cập nhật nhóm người dùng',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Lấy dữ liệu cũ của nhóm người dùng
$body = getBody('get');

if (!empty($body['id'])) {
    $groupId = $body['id'];

    // Kiểm tra $groupId có tồn tại trong database hay không?
    // Nếu tồn tại => lấy ra thông tin
    // Nếu không tồn tại => Chuyển hướng về trang lists
    $groupDetail = firstRaw("SELECT * FROM groups WHERE id=$groupId");
    if (!empty($groupDetail)) {
        // Tồn tại
        // Gán giá trị $groupDetail vào flashData
        setFlashData('groupDetail', $groupDetail);

    } else {
        redirect('admin?module=groups');
    }
} else {
    redirect('admin?module=groups');
}

// Xử lý sửa nhóm người dùng
if (isPost()) {
    // Validate form
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = []; // Mảng lưu trữ các lỗi

    // Validate Họ tên: Bắt buộc nhập, => 3 ký tự
    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Tên nhóm bắt buộc phải nhập';
    } else {
        if (strlen(trim($body['name']))<3) {
            $errors['name']['min'] = 'Họ tên phải lớn hơn hoặc bằng 3 ký tự';
        }
    }

    // Kiểm tra mảng $errors
    if (empty($errors)) {
        // Không có lỗi xảy ra
        $dataUpdate = [
            'name' => $body['name'],
            'update_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=$groupId";
        $updateStatus = update('groups', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật nhóm người dùng thành công');
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

    redirect('admin?module=groups&action=edit&id='.$groupId);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');
$groupDetail = getFlashData('groupDetail');
if (empty($old) && !empty($groupDetail)) {
    $old = $groupDetail;
}
?>
    <!-- Main content -->
    <section class="content">
        <form action="" method="post">
            <div class="form-group">
                <?php
                getMsg($msg, $msg_type);
                ?>
                <label for="">Tên nhóm</label>
                <input type="text" name="name" class="form-control" placeholder="Tên nhóm..."
                       value="<?php echo old('name', $old); ?>">
                <?php echo form_error('name', $errors,
                    '<span class="error">', '</span>') ?>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo getLinkAdmin('groups', 'lists'); ?>"
               class="btn btn-success" style="margin-left: 10px">Quay lại</a>
        </form>
    </section>
<?php
layout('footer', 'admin', $data);