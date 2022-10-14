<?php
// Lấy dữ liệu cũ
$body = getBody('get');

if (!empty($body['id'])) {
    $contactTypeId = $body['id'];

    // Kiểm tra $contactTypeId có tồn tại trong database hay không?
    // Nếu tồn tại => lấy ra thông tin
    // Nếu không tồn tại => Chuyển hướng về trang lists
    $contactTypeDetail = firstRaw("SELECT * FROM contact_type WHERE id=$contactTypeId");
    if (!empty($contactTypeDetail)) {
        // Tồn tại
        // Gán giá trị $contactTypeDetail vào flashData
        setFlashData('contactTypeDetail', $contactTypeDetail);

    } else {
        redirect('admin?module=contact_type');
    }
} else {
    redirect('admin?module=contact_type');
}

// Xử lý sửa phòng ban
if (isPost()) {
    // Validate form
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = []; // Mảng lưu trữ các lỗi

    // Validate tên phòng ban: Bắt buộc nhập, >= 4 ký tự
    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Tên phòng ban bắt buộc phải nhập';
    } else {
        if (strlen(trim($body['name']))<3) {
            $errors['name']['min'] = 'Họ tên phải lớn hơn hoặc bằng 4 ký tự';
        }
    }

    // Kiểm tra mảng $errors
    if (empty($errors)) {
        // Không có lỗi xảy ra
        $dataUpdate = [
            'name' => $body['name'],
            'update_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=$contactTypeId";
        $updateStatus = update('contact_type', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật phòng ban thành công');
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

    redirect('admin?module=contact_type&action=lists&view=edit&id='.$contactTypeId);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');
$contactTypeDetail = getFlashData('contactTypeDetail');
if (empty($old) && !empty($contactTypeDetail)) {
    $old = $contactTypeDetail;
}
?>
<h4>Cập nhật phòng ban</h4>
<form action="" method="post">
    <div class="form-group">
        <label for="">Tên dự án</label>
        <input type="text" class="form-control" name="name" placeholder="Tên phòng ban dự án..."
               value="<?php echo old('name', $old); ?>">
        <?php echo form_error('name', $errors,
            '<span class="error">', '</span>') ?>
    </div>
    <a href="<?php echo getLinkAdmin('contact_type', 'lists'); ?>" class="btn btn-primary" style="float: right">Quay lại</a>
    <button type="submit" class="btn btn-success" style="float: right; margin-right: 10px;">Cập nhật</button>
</form>
