<?php
if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => getOption('portfolio_title')
];
layout('header', 'client', $data);
layout('breadcrumb', 'client', $data);

$isPortfolioPage = true;

require_once _WEB_PATH_ROOT.'/modules/home/contents/portfolio.php';

layout('footer', 'client');
?>