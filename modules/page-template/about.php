<?php
if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => getOption('about_title')
];
layout('header', 'client', $data);
layout('breadcrumb', 'client', $data);

require_once _WEB_PATH_ROOT.'/modules/home/contents/about.php';
require_once _WEB_PATH_ROOT.'/modules/home/contents/partner.php';
?>

<?php
layout('footer', 'client');
?>
