<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng duyệt comments
 * */
$body = getBody();
if (!empty($body['id'])) {
    $commentId = $body['id'];

    $commentDetailRows = getRows("SELECT id FROM comments WHERE id=$commentId");
    if ($commentDetailRows > 0) {

        $allowedStatus = [
          0, 1
        ];

        if (isset($body['status']) && in_array($body['status'], $allowedStatus)) {
            $status = $body['status'];

            // Thực hiện duyệt
            $condition = "id=$commentId";

            $updateStatus = update('comments', [
                'status' => $status
            ], $condition);

            if (!empty($updateStatus)) {
                if ($status==0) {
                    $msg = 'Bỏ duyệt';
                } else {
                    $msg = 'Duyệt';
                }
                setFlashData('msg', $msg.' bình luận thành công');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Duyệt bình luận không thành công. Vui lòng thử lại sau');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Liên kết không tồn tại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Bình luận không tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=comments');