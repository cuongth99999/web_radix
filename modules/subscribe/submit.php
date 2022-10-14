<?php
if (!defined('_INCODE')) die('Access Denied...');

if (isPost()) {
    $body = getBody();

    $errors = [];

    // Validate name
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Tên không được để trống';
    } else {
        if (strlen(trim($body['fullname']))<5) {
            $errors['fullname']['min'] = 'Tên phải lớn hơn hoặc bằng 5 ký tự';
        }
    }

    // Validate email: Bắt buộc phải nhập, Định dạng email, Email phải duy nhất
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        if (!isEmail(trim($body['email']))) {
            $errors['email']['isEmail'] = 'Email không hợp lệ';
        }
    }

    if (empty($errors)) {
        // Xử lý submit
        $dataInsert = [
            'fullname' => trim(strip_tags($body['fullname'])),
            'email' => trim(strip_tags($body['email'])),
            'status' => 0,
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('subscribe', $dataInsert);

        if ($insertStatus) {
            setFlashData('msg', 'Đăng ký thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Bạn không đăng ký vào lúc này. Vui lòng thử lại sau!');
            setFlashData('msg_type', 'danger');
        }
    } else {
        // Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('erros', $errors);
        setFlashData('old', $body);
    }

    $urlBack = $_SERVER['HTTP_REFERER'].'#newsletter';
    redirect($urlBack, true);
}