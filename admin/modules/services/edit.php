<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File dùng để cập nhật thông tin dịch vụ
 * */
$data = [
    'pageTitle' => 'Cập nhật dịch vụ',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Lấy dữ liệu cũ của dịch vụ
$body = getBody('get');

if (!empty($body['id'])) {
    $serviceId = $body['id'];

    // Kiểm tra $groupId có tồn tại trong database hay không?
    // Nếu tồn tại => lấy ra thông tin
    // Nếu không tồn tại => Chuyển hướng về trang lists
    $serviceDetail = firstRaw("SELECT * FROM services WHERE id=$serviceId");
    if (!empty($serviceDetail)) {
        // Tồn tại
        // Gán giá trị $serviceDetail vào flashData
        setFlashData('serviceDetail', $serviceDetail);

    } else {
        redirect('admin?module=services');
    }
} else {
    redirect('admin?module=services');
}

// Xử lý sửa nhóm người dùng
if (isPost()) {
    // Validate form
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = []; // Mảng lưu trữ các lỗi

    //Validate tên dịch vụ: Bắt buộc nhập

    if (empty(trim($body['name']))){
        $errors['name']['required'] = 'Tên dịch vụ bắt buộc phải nhập';
    }

    //Validate slug: Bắt buộc nhập
    if (empty(trim($body['slug']))){
        $errors['slug']['required'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    //Validate icon: Bắt buộc nhập
    if (empty(trim($body['icon']))){
        $errors['icon']['required'] = 'Icon bắt buộc phải nhập';
    }

    //Validate nội dung: Bắt buộc phải nhập
    if (empty(trim($body['content']))){
        $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
    }

    // Kiểm tra mảng $errors
    if (empty($errors)) {
        // Không có lỗi xảy ra
        $dataUpdate = [
            'name' => trim($body['name']),
            'slug' => trim($body['slug']),
            'icon' => trim($body['icon']),
            'description' => trim($body['description']),
            'content' => trim($body['content']),
            'update_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=$serviceId";
        $updateStatus = update('services', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật dịch vụ thành công');
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

    redirect('admin?module=services&action=edit&id='.$serviceId);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');
$serviceDetail = getFlashData('serviceDetail');
if (empty($old) && !empty($serviceDetail)) {
    $old = $serviceDetail;
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
                    <label for="">Tên dịch vụ</label>
                    <input type="text" class="form-control slug" name="name" placeholder="Tên dịch vụ..." value="<?php echo old('name', $old); ?>"/>
                    <?php echo form_error('name', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Đường dẫn tĩnh</label>
                    <input type="text" class="form-control render-slug" name="slug" placeholder="Đường dẫn tĩnh..." value="<?php echo old('slug', $old); ?>"/>
                    <?php echo form_error('slug', $errors, '<span class="error">', '</span>'); ?>
                    <p class="render-link"><b>Link</b>: <span></span></p>
                </div>

                <div class="form-group">
                    <label for="">Icon</label>
                    <div class="row ckfinder-group">
                        <div class="col-10">
                            <input type="text" class="form-control image-render" name="icon" placeholder="Đường dẫn ảnh hoặc mã icon..." value="<?php echo old('icon', $old); ?>"/>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-success btn-block choose-image">Chọn ảnh</button>
                        </div>
                    </div>

                    <?php echo form_error('icon', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Mô tả ngắn</label>
                    <textarea name="description" class="form-control" placeholder="Mô tả ngắn..."><?php echo old('description', $old) ?></textarea>
                    <?php echo form_error('description', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Nội dung</label>
                    <textarea name="content" class="form-control editor"><?php echo old('content', $old) ?></textarea>
                    <?php echo form_error('content', $errors, '<span class="error">', '</span>'); ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo getLinkAdmin('services', 'lists'); ?>"
               class="btn btn-success" style="margin-left: 10px">Quay lại</a>
        </form>
    </section>
<?php
layout('footer', 'admin', $data);
