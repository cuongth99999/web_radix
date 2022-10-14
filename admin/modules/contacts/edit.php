<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => 'Cập nhật liên hệ'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Lấy dữ liệu cũ
$body = getBody('get');

if (!empty($body['id'])) {
    $contactId = $body['id'];

    $contactDetail = firstRaw("SELECT * FROM contacts WHERE id=$contactId");
    if (!empty($contactDetail)) {
        // Tồn tại
        // Gán giá trị $contactDetail vào flashData
        setFlashData('contactDetail', $contactDetail);
    } else {
        redirect('admin?module=contacts');
    }
} else {
    redirect('admin?module=contacts');
}

//Xử lý cập nhật
if (isPost()){

    //Validate form
    $body = getBody(); //Lấy tất cả dữ liệu trong form

    $errors = []; //Mảng lưu trữ các lỗi

    //Validate tên blog: Bắt buộc nhập
    if (empty(trim($body['fullname']))){
        $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
    }

    if (empty(trim($body['email']))){
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    }

    //Validate nội dung: Bắt buộc phải nhập
    if (empty(trim($body['message']))){
        $errors['message']['required'] = 'Nội dung bắt buộc phải nhập';
    }

    //Validate phòng ban: Bắt buộc phải chọn
    if (empty(trim($body['type_id']))){
        $errors['type_id']['required'] = 'Phòng ban bắt buộc phải chọn';
    }

    //Kiểm tra mảng $errors
    if (empty($errors)) {
        //Không có lỗi xảy ra

        $dataUpdate = [
            'fullname' => trim($body['fullname']),
            'email' => trim($body['email']),
            'message' => trim($body['message']),
            'type_id' => trim($body['type_id']),
            'status' => trim($body['status']),
            'note' => trim($body['note']),
            'update_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=$contactId";
        $updateStatus = update('contacts', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật liên hệ thành công');
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

    redirect('admin?module=contacts&action=edit&id='.$contactId);
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
$contactDetail = getFlashData('contactDetail');
if (empty($old) && !empty($contactDetail)) {
    $old = $contactDetail;
}

// Lấy dữ liệu tất cả phòng ban
$allContactType = getRaw("SELECT * FROM contact_type ORDER BY name");

?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="" method="post">
                <?php
                getMsg($msg, $msgType);
                ?>
                <div class="form-group">
                    <label for="">Họ tên</label>
                    <input type="text" class="form-control" name="fullname" placeholder="Họ tên..." value="<?php echo old('fullname', $old); ?>"/>
                    <?php echo form_error('fullname', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Email..." value="<?php echo old('email', $old); ?>"/>
                    <?php echo form_error('email', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Nội dung</label>
                    <textarea rows="10" name="message" class="form-control" placeholder="Nội dung..."><?php echo old('message', $old) ?></textarea>
                    <?php echo form_error('message', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Phòng ban</label>
                    <select class="form-control" name="type_id">
                        <option value="0">Chọn phòng ban</option>
                        <?php
                        if (!empty($allContactType)) {
                            foreach ($allContactType as $item) {
                                ?>
                                <option value="<?php echo $item['id']; ?>" <?php echo (old('type_id', $old)==$item['id'])?'selected':false; ?>
                                ><?php echo $item['name']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <?php echo form_error('type_id', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="0" <?php echo (old('status', $old)==0)?'selected':false; ?>>
                            Chưa xử lý</option>
                        <option value="1" <?php echo (old('status', $old)==1)?'selected':false; ?>>
                            Đã xử lý</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Ghi chú</label>
                    <textarea rows="10" name="note" class="form-control" placeholder="Ghi chú..."><?php echo old('note', $old) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?php echo getLinkAdmin('contacts', 'lists'); ?>" class="btn btn-success">Quay lại</a>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
