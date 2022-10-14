<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng xóa nhóm người dùng
 * */
$body = getBody();
if (!empty($body['id'])) {
    $groupId = $body['id'];
    $groupDetailRows = getRows("SELECT id FROM groups WHERE id=$groupId");
    if ($groupDetailRows > 0) {
        // Kiểm tra xem trong nhóm còn người dùng không
        $userNum = getRows("SELECT id FROM users WHERE group_id=$groupId");
        if ($userNum > 0) {
            setFlashData('msg', 'Trong nhóm vẫn còn '.$userNum.' người dùng.');
            setFlashData('msg_type', 'danger');
        } else {
            // Thực hiện xóa nhóm người dùng
            $condition = "id=$groupId";

            $deleteStatus = delete('groups', $condition);
            if (!empty($deleteStatus)) {
                setFlashData('msg', 'Xóa nhóm người dùng thành công');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Xóa nhóm người dùng không thành công. Vui lòng thử lại sau');
                setFlashData('msg_type', 'danger');
            }
        }
    } else {
        setFlashData('msg', 'Nhóm người dùng không tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=groups');