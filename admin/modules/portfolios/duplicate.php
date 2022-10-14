<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng nhân bản dự án
 * */
$body = getBody();
if (!empty($body['id'])) {
    $portfolioId = $body['id'];
    $portfolioDetail = firstRaw("SELECT * FROM portfolios WHERE id=$portfolioId");
    if (!empty($portfolioDetail)) {
        // Loại bỏ thời gian tạo (create_at), thời gian cập nhật (update_at), id
        $portfolioDetail['create_at'] = date('Y-m-d H:i:s');

        unset($portfolioDetail['id']);
        unset($portfolioDetail['update_at']);

        $duplicate = $portfolioDetail['duplicate'];
        $duplicate++;

        $name = $portfolioDetail['name'].' ('.$duplicate.')';

        $portfolioDetail['name'] = $name;

        $insertStatus = insert('portfolios', $portfolioDetail);
        if ($insertStatus){
            setFlashData('msg', 'Nhân bản dự án thành công');
            setFlashData('msg_type', 'success');

            update('portfolios',
                [
                    'duplicate' => $duplicate,
                ],
                "id=$portfolioId"
            );
        }
    } else {
        setFlashData('msg', 'Dự án không tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=portfolios');