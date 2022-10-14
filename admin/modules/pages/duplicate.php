<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng nhân bản trang
 * */
$body = getBody();
if (!empty($body['id'])) {
    $pageId = $body['id'];
    $pageDetail = firstRaw("SELECT * FROM pages WHERE id=$pageId");
    if (!empty($pageDetail)) {
        // Loại bỏ thời gian tạo (create_at), thời gian cập nhật (update_at), id
        $serviceDetail['create_at'] = date('Y-m-d H:i:s');

        unset($pageDetail['id']);
        unset($pageDetail['update_at']);

        $duplicate = $pageDetail['duplicate'];
        $duplicate++;

        $title = $pageDetail['title'].' ('.$duplicate.')';

        $pageDetail['title'] = $title;

        $insertStatus = insert('pages', $pageDetail);
        if ($insertStatus){
            setFlashData('msg', 'Nhân bản trang thành công');
            setFlashData('msg_type', 'success');

            update('pages',
                [
                    'duplicate' => $duplicate,
                ],
                "id=$pageId"
            );
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