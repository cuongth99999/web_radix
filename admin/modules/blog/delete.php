<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng xóa blog
 * */
$body = getBody();
if (!empty($body['id'])) {
    $blogId = $body['id'];
    $blogDetailRows = getRows("SELECT id FROM blog WHERE id=$blogId");
    if ($blogDetailRows > 0) {
        // Thực hiện xóa
        $condition = "id=$blogId";

        $deleteStatus = delete('blog', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa blog thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa blog không thành công. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Blog tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=blog');