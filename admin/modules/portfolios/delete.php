<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng xóa dự án
 * */
$body = getBody();
if (!empty($body['id'])) {
    $portfolioId = $body['id'];
    $portfolioDetailRows = getRows("SELECT id FROM portfolios WHERE id=$portfolioId");
    if ($portfolioDetailRows > 0) {
        // Xử lý xóa thư viện ảnh
        delete('portfolio_images', "portfolio_id=".$body['id']);

        // Thực hiện xóa
        $condition = "id=$portfolioId";

        $deleteStatus = delete('portfolios', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa dự án thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa dự án không thành công. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
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