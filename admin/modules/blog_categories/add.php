<?php

//Lấy userId đăng nhập
$userId = isLogin()['user_id'];

// Xử lý thêm danh mục blog
if (isPost()) {
// Validate form
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = []; // Mảng lưu trữ các lỗi

// Validate tên danh mục dự án: Bắt buộc nhập, => 3 ký tự
    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Tên danh mục blog bắt buộc phải nhập';
    } else {
        if (strlen(trim($body['name'])) < 4) {
            $errors['name']['min'] = 'Tên danh mục blog phải lớn hơn hoặc bằng 4 ký tự';
        }
    }

// Kiểm tra mảng $errors
    if (empty($errors)) {
// Không có lỗi xảy ra
        $dataInsert = [
            'name' => $body['name'],
            'user_id' => $userId,
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('blog_categories', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm danh mục dự án thành công');
            setFlashData('msg_type', 'success');

            redirect('admin?module=blog_categories'); // Chuyển hướng sang trang danh sách
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');

            redirect('admin?module=blog_categories'); // Load lại trang danh mục
        }
    } else {
// Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('erros', $errors);
        setFlashData('old', $body);
        redirect('admin?module=blog_categories'); // Load lại trang danh mục
    }
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');
?>
<h4>Thêm Blog</h4>
<form action="" method="post">
    <div class="form-group">
        <label for="">Tên danh mục</label>
        <input type="text" class="form-control" name="name" placeholder="Tên danh mục blog..."
               value="<?php echo old('name', $old); ?>">
        <?php echo form_error('name', $errors,
            '<span class="error">', '</span>') ?>
    </div>
    <button type="submit" class="btn btn-success" style="float: right">Thêm mới</button>
</form>