<?php
if (!defined('_INCODE')) die('Access Denied...');

autoRemoveTokenLogin();

?>

<html>
<head>
    <title><?php echo !empty($data['pageTitle'])?$data['pageTitle']:'THC' ?></title>
    <meta charset="UTF-8"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets//css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES; ?>/assets/plugins/fontawesome-free/css/all.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATES ?>/assets/css/auth.css?ver=<?php echo rand(); ?>">
</head>
<body>