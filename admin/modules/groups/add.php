<?php
if (!defined('_INCODE')) die('Access Denied...');
$data = [
    'pageTitle' => 'Thêm nhóm người dùng'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Xử lý thêm nhóm người dùng
if (isPost()) {
    // Validate form
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = []; // Mảng lưu trữ các lỗi

    // Validate tên nhóm: Bắt buộc nhập, => 3 ký tự
    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Tên nhóm bắt buộc phải nhập';
    } else {
        if (strlen(trim($body['name']))<3) {
            $errors['name']['min'] = 'Tên nhóm phải lớn hơn hoặc bằng 3 ký tự';
        }
    }

    // Kiểm tra mảng $errors
    if (empty($errors)) {
        // Không có lỗi xảy ra
        $dataInsert = [
            'name' => $body['name'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('groups', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm nhóm người dùng thành công');
            setFlashData('msg_type', 'success');

            redirect('admin?module=groups'); // Chuyển hướng sang trang danh sách người dùng
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');

            redirect('admin?module=groups&action=add'); // Load lại trang thêm nhóm người dùng
        }
    } else {
        // Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('erros', $errors);
        setFlashData('old', $body);
        redirect('admin?module=groups&action=add'); // Load lại trang thêm nhóm người dùng
    }
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');
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
        <button type="submit" class="btn btn-primary">Thêm mới</button>
        <a href="<?php echo getLinkAdmin('groups', 'lists'); ?>"
           class="btn btn-success" style="margin-left: 10px">Quay lại</a>
    </form>
</section>

<?php
layout('footer', 'admin', $data);