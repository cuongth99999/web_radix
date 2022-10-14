<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => 'Cập nhật bình luận'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Lấy dữ liệu cũ
$body = getBody('get');

if (!empty($body['id'])) {
    $commentId = $body['id'];

    $commentDetail = firstRaw("SELECT comments.*, blog.title, users.fullname, users.email as user_email, groups.name as group_name FROM comments INNER JOIN blog ON comments.blog_id=blog.id
                              LEFT JOIN users ON comments.user_id=users.id LEFT JOIN groups ON users.group_id=groups.id 
                                                                         WHERE comments.id=$commentId");

    if (!empty($commentDetail)) {
        // Tồn tại
        // Gán giá trị $commentDetail vào flashData
        setFlashData('commentDetail', $commentDetail);

    } else {
        redirect('admin?module=comments');
    }
} else {
    redirect('admin?module=comments');
}

//Xử lý cập nhật comments
if (isPost()){

    //Validate form
    $body = getBody(); //Lấy tất cả dữ liệu trong form

    $errors = []; //Mảng lưu trữ các lỗi

    if (empty($commentDetail['user_id'])) {
        //Validate tên blog: Bắt buộc nhập
        if (empty(trim($body['name']))){
            $errors['name']['required'] = 'Họ tên bắt buộc phải nhập';
        }

        if (empty(trim($body['email']))){
            $errors['email']['required'] = 'Email bắt buộc phải nhập';
        } else {
            if (!isEmail(trim($body['email']))) {
                $errors['email']['isEmail'] = 'Email không hợp lệ';
            }
        }
    }

    //Validate nội dung: Bắt buộc phải nhập
    if (empty(trim($body['content']))){
        $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
    }

    //Kiểm tra mảng $errors
    if (empty($errors)) {
        //Không có lỗi xảy ra

        $dataUpdate = [
            'content' => trim($body['content']),
            'status' => trim($body['status']),
            'update_at' => date('Y-m-d H:i:s')
        ];

        if (empty($commentDetail['user_id'])) {
            $dataUpdate = array_merge($dataUpdate, [
                'name' => trim($body['name']),
                'email' => trim($body['email']),
                'website' => trim($body['website'])
            ]);
        }

        $condition = "id=$commentId";

        $updateStatus = update('comments', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật bình luận thành công');
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

    redirect('admin?module=comments&action=edit&id='.$commentId);
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
$commentDetail = getFlashData('commentDetail');
if (empty($old) && !empty($commentDetail)) {
    $old = $commentDetail;
}

?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="" method="post">
                <?php
                getMsg($msg, $msgType);
                ?>
                <p><strong>Bình luận từ bài viết:</strong> <a href="<?php echo _WEB_HOST_ROOT.'?module=blog&&action=detail&id='.$commentDetail['blog_id']; ?>" target="_blank">
                        <?php echo $commentDetail['title']; ?>
                    </a></p>

                <?php
                if (!empty($commentDetail['parent_id'])) {
                    $commentData = getComment($commentDetail['parent_id']);
                    if (!empty($commentData['name'])) {
                        echo '<p><strong>Trả lời bình luận: </strong>'.$commentData['name'].'</p>';
                    }
                }
                ?>

                <?php if (empty($commentDetail['user_id'])): ?>
                <h4>Thông tin cá nhân</h4>
                <div class="form-group">
                    <label for="">Họ tên</label>
                    <input type="text" class="form-control" name="name" placeholder="Họ và tên..." value="<?php echo old('name', $old); ?>"/>
                    <?php echo form_error('name', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Email..." value="<?php echo old('email', $old); ?>"/>
                    <?php echo form_error('email', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Website</label>
                    <input type="text" class="form-control" name="website" placeholder="Website..." value="<?php echo old('website', $old); ?>"/>
                </div>
                <?php else: ?>
                <h4>Thông tin người dùng</h4>
                <p>- Họ tên: <?php echo $commentDetail['fullname']; ?></p>
                <p>- Email: <?php echo $commentDetail['user_email']; ?></p>
                <p>- Nhóm: <?php echo $commentDetail['group_name']; ?></p>
                <?php endif; ?>

                <h4>Chi tiết bình luận</h4>
                <div class="form-group">
                    <label for="">Nội dung</label>
                    <textarea name="content" rows="8" class="form-control" placeholder="Nội dung..."><?php echo old('content', $old); ?></textarea>
                    <?php echo form_error('content', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="0" <?php echo old('status', $old)==0?'selected':false; ?>>Chưa duyệt</option>
                        <option value="1" <?php echo old('status', $old)==1?'selected':false; ?>>Đã duyệt</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?php echo getLinkAdmin('comments', 'lists'); ?>" class="btn btn-success">Quay lại</a>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
