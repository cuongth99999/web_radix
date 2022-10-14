<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File dùng để cập nhật thông tin trang
 * */
$data = [
    'pageTitle' => 'Cập nhật trang',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Lấy dữ liệu cũ
$body = getBody('get');

if (!empty($body['id'])) {
    $pageId = $body['id'];

    // Kiểm tra $pageId có tồn tại trong database hay không?
    // Nếu tồn tại => lấy ra thông tin
    // Nếu không tồn tại => Chuyển hướng về trang lists
    $pageDetail = firstRaw("SELECT * FROM pages WHERE id=$pageId");
    if (!empty($pageDetail)) {
        // Tồn tại
        // Gán giá trị $pageDetail vào flashData
        setFlashData('pageDetail', $pageDetail);

    } else {
        redirect('admin?module=pages');
    }
} else {
    redirect('admin?module=pages');
}

// Xử lý sửa nhóm người dùng
if (isPost()) {
    // Validate form
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = []; // Mảng lưu trữ các lỗi

    //Validate tên trang: Bắt buộc nhập

    if (empty(trim($body['title']))){
        $errors['title']['required'] = 'Tên trang bắt buộc phải nhập';
    }

    //Validate slug: Bắt buộc nhập
    if (empty(trim($body['slug']))){
        $errors['slug']['required'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    //Validate nội dung: Bắt buộc phải nhập
    if (empty(trim($body['content']))){
        $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
    }

    // Kiểm tra mảng $errors
    if (empty($errors)) {
        // Không có lỗi xảy ra
        $dataUpdate = [
            'title' => trim($body['title']),
            'slug' => trim($body['slug']),
            'content' => trim($body['content']),
            'update_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=$pageId";
        $updateStatus = update('pages', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật trang thành công');
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

    redirect('admin?module=pages&action=edit&id='.$pageId);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');
$pageDetail = getFlashData('pageDetail');
if (empty($old) && !empty($pageDetail)) {
    $old = $pageDetail;
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
                    <label for="">Tiêu đề</label>
                    <input type="text" class="form-control slug" name="title" placeholder="Tiêu đề trang..." value="<?php echo old('title', $old); ?>"/>
                    <?php echo form_error('title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Đường dẫn tĩnh</label>
                    <input type="text" class="form-control render-slug" name="slug" placeholder="Đường dẫn tĩnh..." value="<?php echo old('slug', $old); ?>"/>
                    <?php echo form_error('slug', $errors, '<span class="error">', '</span>'); ?>
                    <p class="render-link"><b>Link</b>: <span></span></p>
                </div>

                <div class="form-group">
                    <label for="">Nội dung</label>
                    <textarea name="content" class="form-control editor"><?php echo old('content', $old) ?></textarea>
                    <?php echo form_error('content', $errors, '<span class="error">', '</span>'); ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo getLinkAdmin('pages', 'lists'); ?>"
               class="btn btn-success" style="margin-left: 10px">Quay lại</a>
        </form>
    </section>
<?php
layout('footer', 'admin', $data);
