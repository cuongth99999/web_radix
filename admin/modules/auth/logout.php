<?php
if (!defined('_INCODE')) die('Access Deined...');
/*File này chứa chức năng đăng xuất*/

if (isLogin()){
    $token = getSession('loginToken');
    delete('logintoken', "token='$token'");
    removeSession('loginToken');
    if (!empty($_SERVER['HTTP_REFERER'])) {
        redirect($_SERVER['HTTP_REFERER'], true);
    } else {
        redirect('admin?module=auth&action=login');
    }
}