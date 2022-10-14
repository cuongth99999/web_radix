<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này chứa chức năng xóa danh mục
 * */
$body = getBody();
if (!empty($body['id'])) {
    $cateId = $body['id'];
    $cateDetailRows = getRows("SELECT id FROM portfolio_categories WHERE id=$cateId");
    if ($cateDetailRows > 0) {
        // Kiểm tra xem trong danh mục còn dự án hay không
        $portfolioNum = getRows("SELECT id FROM portfolios WHERE portfolio_category_id=$cateId");
        if ($portfolioNum > 0) {
            setFlashData('msg', 'Trong danh mục vẫn còn '.$portfolioNum.' dự án.');
            setFlashData('msg_type', 'danger');
        } else {
            // Thực hiện xóa danh mục dự án
            $condition = "id=$cateId";

            $deleteStatus = delete('portfolio_categories', $condition);
            if (!empty($deleteStatus)) {
                setFlashData('msg', 'Xóa danh mục dự án thành công');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Xóa danh mục dự án không thành công. Vui lòng thử lại sau');
                setFlashData('msg_type', 'danger');
            }
        }
    } else {
        setFlashData('msg', 'Danh mục dự án không tồn tại trên hệ thống');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=portfolio_categories');