<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng xóa bình luận
 * */
$body = getBody();
if (!empty($body['id'])) {
    $commentId = $body['id'];
    $commentDetailRows = getRows("SELECT id FROM comments WHERE id=$commentId");
    if ($commentDetailRows > 0) {

        // Truy vấn lấy tất cả comments
        $commentData = getRaw("SELECT * FROM comments");

        $commentIdArr = getCommentReply($commentData, $commentId);

        $commentIdArr[] = $commentId;

        $commentIdStr = implode(',', $commentIdArr);

        // Thực hiện xóa
        $condition = "id IN($commentIdStr)";

        $deleteStatus = delete('comments', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa bình luận thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa bình luận không thành công. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Bình luận tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=comments');