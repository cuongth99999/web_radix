<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng nhân bản blog
 * */
$body = getBody();
if (!empty($body['id'])) {
    $blogId = $body['id'];
    $blogDetail = firstRaw("SELECT * FROM blog WHERE id=$blogId");
    if (!empty($blogDetail)) {
        // Loại bỏ thời gian tạo (create_at), thời gian cập nhật (update_at), id
        $serviceDetail['create_at'] = date('Y-m-d H:i:s');

        unset($blogDetail['id']);
        unset($blogDetail['update_at']);

        $duplicate = $blogDetail['duplicate'];
        $duplicate++;

        $title = $blogDetail['title'].' ('.$duplicate.')';

        $blogDetail['title'] = $title;

        $insertStatus = insert('blog', $blogDetail);
        if ($insertStatus){
            setFlashData('msg', 'Nhân bản blog thành công');
            setFlashData('msg_type', 'success');

            update('blog',
                [
                    'duplicate' => $duplicate,
                ],
                "id=$blogId"
            );
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