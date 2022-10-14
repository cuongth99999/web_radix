<?php
// Lấy dữ liệu cũ
$body = getBody('get');

if (!empty($body['id'])) {
    $portfolio_categorieId = $body['id'];

    // Kiểm tra $portfolio_categorieId có tồn tại trong database hay không?
    // Nếu tồn tại => lấy ra thông tin
    // Nếu không tồn tại => Chuyển hướng về trang lists
    $portfolio_categorieDetail = firstRaw("SELECT * FROM portfolio_categories WHERE id=$portfolio_categorieId");
    if (!empty($portfolio_categorieDetail)) {
        // Tồn tại
        // Gán giá trị $portfolio_categorieDetail vào flashData
        setFlashData('portfolio_categorieDetail', $portfolio_categorieDetail);

    } else {
        redirect('admin?module=portfolio_categories');
    }
} else {
    redirect('admin?module=portfolio_categories');
}

// Xử lý sửa danh mục
if (isPost()) {
    // Validate form
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = []; // Mảng lưu trữ các lỗi

    // Validate tên danh mục: Bắt buộc nhập, >= 4 ký tự
    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Tên danh mục bắt buộc phải nhập';
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

        $condition = "id=$portfolio_categorieId";
        $updateStatus = update('portfolio_categories', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật danh mục thành công');
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

    redirect('admin?module=portfolio_categories&action=lists&view=edit&id='.$portfolio_categorieId);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');
$portfolio_categorieDetail = getFlashData('portfolio_categorieDetail');
if (empty($old) && !empty($portfolio_categorieDetail)) {
    $old = $portfolio_categorieDetail;
}
?>
<h4>Cập nhật danh mục</h4>
<form action="" method="post">
    <div class="form-group">
        <label for="">Tên dự án</label>
        <input type="text" class="form-control" name="name" placeholder="Tên danh mục dự án..."
               value="<?php echo old('name', $old); ?>">
        <?php echo form_error('name', $errors,
            '<span class="error">', '</span>') ?>
    </div>
    <a href="<?php echo getLinkAdmin('portfolio_categories', 'lists'); ?>" class="btn btn-primary" style="float: right">Quay lại</a>
    <button type="submit" class="btn btn-success" style="float: right; margin-right: 10px;">Cập nhật</button>
</form>
