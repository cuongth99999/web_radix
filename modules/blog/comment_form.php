<?php

$commentName = null;
if (!empty(getBody('get')['comment_id'])) {
    $commentId = getBody('get')['comment_id'];
    $commentName = $commentData[$commentId]['name'];
}

if (!empty(isLogin())) {
    $userId = isLogin()['user_id'];
}

if (isPost()) {
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = [];

    if (empty($userId)) {
        // Validate name
        if (empty(trim($body['name']))) {
            $errors['name']['required'] = 'Tên không được để trống';
        } else {
            if (strlen(trim($body['name']))<5) {
                $errors['name']['min'] = 'Tên phải lớn hơn hoặc bằng 5 ký tự';
            }
        }

        // Validate email: Bắt buộc phải nhập, Định dạng email, Email phải duy nhất
        if (empty(trim($body['email']))) {
            $errors['email']['required'] = 'Email bắt buộc phải nhập';
        } else {
            if (!isEmail(trim($body['email']))) {
                $errors['email']['isEmail'] = 'Email không hợp lệ';
            }
        }
    }

    // Validate content
    if (empty($body['content'])) {
        $errors['content']['required'] = 'Nội dung bình luận không được để trống';
    } else {
        if (strlen(trim($body['content']))<10) {
            $errors['content']['min'] = 'Nội dung bình luận phải lớn hơn hoặc bằng 10 ký tự';
        }
    }

    if (empty($errors)) {

        // Lưu tất cả thông tin vào cookie
        if (empty($userId)) {
            $commentInfo = [
                'name' => trim(strip_tags($body['name'])),
                'email' => trim(strip_tags($body['email'])),
                'website' => trim(strip_tags($body['website']))
            ];

            setcookie('commentInfo', json_encode($commentInfo), time()+86400*365);
        }

        // Xử lý submit
        $dataInsert = [
            'content' => trim(strip_tags($body['content'])),
            'parent_id' => 0,
            'blog_id' => $id,
            'user_id' => !empty($userId)?$userId:null,
            'status' => 0,
            'create_at' => date('Y-m-d H:i:s')
        ];

        if (empty($userId)) {
            $dataInsert['name'] = trim(strip_tags($body['name']));
            $dataInsert['email'] = trim(strip_tags($body['email']));
            $dataInsert['website'] = trim(strip_tags($body['website']));
        }

        if (!empty($commentId)) {
            $dataInsert['parent_id'] = $commentId;
            $dataInsert['status'] = 1; // Bỏ duyệt cmt khi reply
        }

        $insertStatus = insert('comments', $dataInsert);

        if ($insertStatus) {
            if (empty($commentId)) {
                setFlashData('msg', 'Bình luận đã được gửi đi thành công. Vui lòng chờ duyệt!');
            } else {
                setFlashData('msg', 'Bình luận đã được gửi đi thành công.');
            }
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Bạn không thể gửi bình luận vào lúc này! Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }

    } else {
        // Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('erros', $errors);
        setFlashData('old', $body);
    }
//    redirect('?module=blog&action=detail&id='.$id.'#comment-form');
    $linkBlog = getLinkModule('blog', $id).'#comment-form';
    redirect($linkBlog, true);
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
$old = getFlashData('old');

// Lấy dữ liệu từ cookie
$commentInfo = [];
if (!empty($_COOKIE['commentInfo'])) {
    $commentInfo = json_decode($_COOKIE['commentInfo'], true);
}

?>
<div class="comments-form id=comment-form">
    <h2 class="title"><?php echo (!empty($commentName))?'Trả lời bình luận: '.$commentName.' <a href="'._WEB_HOST_ROOT.'?module=blog&action=detail&id='.$id.'"><i class="fa fa-times" style="padding-left: 20px"></i> Hủy</a>':'Viết bình luận'; ?></h2>
    <?php
    //Check admin login
    if (!empty($userId)) {
        $userDetail = getUserInfo($userId);
        echo '<p>Bạn đang đăng nhập với tài khoản <b>'.$userDetail['fullname'].'</b> - <a href="'._WEB_HOST_ROOT_ADMIN.'?module=auth&action=logout">Đăng xuất</a> </p>';
    }

    ?>
    <!-- Contact Form -->
    <?php
    getMsg($msg, $msg_type);
    ?>
    <form class="form" method="post" action="">
        <div class="row">
            <?php
                if (empty($userId)):
            ?>
            <div class="col-lg-4 col-12">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Tên của bạn..."
                    value="<?php echo old('name', $commentInfo); ?>">
                    <?php echo form_error('name', $errors,
                        '<span class="error">', '</span>') ?>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email của bạn..."
                           value="<?php echo old('email', $commentInfo); ?>">
                    <?php echo form_error('email', $errors,
                        '<span class="error">', '</span>') ?>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="form-group">
                    <input type="url" name="website" placeholder="Website của bạn..."
                           value="<?php echo old('website', $commentInfo); ?>">
                </div>
            </div>
            <?php endif; ?>
            <div class="col-12">
                <div class="form-group">
                    <textarea name="content" rows="5" placeholder="Nội dung bình luận..." ></textarea>
                    <?php echo form_error('content', $errors,
                        '<span class="error">', '</span>') ?>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group button">
                    <button type="submit" class="btn primary">Gửi bình luận</button>
                </div>
            </div>
        </div>
    </form>
    <!--/ End Contact Form -->
</div>