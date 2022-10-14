<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng xóa người dùng
 * */
$body = getBody();
if (!empty($body['id'])) {
    $userId = $body['id'];
    $userDetailRows = getRows("SELECT id FROM users WHERE id=$userId");
    if ($userDetailRows > 0) {
        // Thực hiện xóa người dùng

        // 1. Xóa logintoken
        $deleteToken = delete('logintoken', "user_id=$userId");
        if ($deleteToken) {
            // 2. Xóa user
            $deleteUser = delete('users', "id=$userId");
            if ($deleteUser) {
                setFlashData('msg', 'Xóa người dùng thành công');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Lỗi hệ thống! Vui lòng thử lại sau');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Lỗi hệ thống! Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Người dùng không tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=users');