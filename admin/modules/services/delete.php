<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng xóa dịch vụ
 * */
$body = getBody();
if (!empty($body['id'])) {
    $serviceId = $body['id'];
    $serviceDetailRows = getRows("SELECT id FROM services WHERE id=$serviceId");
    if ($serviceDetailRows > 0) {
        // Thực hiện xóa
        $condition = "id=$serviceId";

        $deleteStatus = delete('services', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa dịch vụ thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa dịch vụ không thành công. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Dịch vụ không tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=services');