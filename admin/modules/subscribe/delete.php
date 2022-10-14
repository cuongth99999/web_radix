<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng xóa đăng ký
 * */
$body = getBody();
if (!empty($body['id'])) {
    $subscribeId = $body['id'];
    $subscribeDetailRows = getRows("SELECT id FROM subscribe WHERE id=$subscribeId");
    if ($subscribeDetailRows > 0) {
        // Thực hiện xóa
        $condition = "id=$subscribeId";

        $deleteStatus = delete('subscribe', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa đăng ký thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa đăng ký không thành công. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Đăng ký tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=subscribe');