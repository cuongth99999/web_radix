<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng xóa liên hệ
 * */
$body = getBody();
if (!empty($body['id'])) {
    $contactId = $body['id'];
    $contactDetailRows = getRows("SELECT id FROM contacts WHERE id=$contactId");
    if ($contactDetailRows > 0) {
        // Thực hiện xóa
        $condition = "id=$contactId";

        $deleteStatus = delete('contacts', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa liên hệ thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa liên hệ không thành công. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Liên hệ tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=contacts');