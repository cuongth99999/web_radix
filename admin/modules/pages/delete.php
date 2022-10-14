<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng xóa trang
 * */
$body = getBody();
if (!empty($body['id'])) {
    $pageId = $body['id'];
    $pageDetailRows = getRows("SELECT id FROM pages WHERE id=$pageId");
    if ($pageDetailRows > 0) {
        // Thực hiện xóa
        $condition = "id=$pageId";

        $deleteStatus = delete('pages', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa trang thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa trang không thành công. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Trang tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=pages');