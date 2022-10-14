<?php
$route['/'] = 'index.php?module=home';
$route['trang-chu.html'] = 'index.php?module=home';
$route['blog/.+-(.+).html'] = 'index.php?module=blog&action=detail&id=$1';
$route['danh-muc/.+-(.+).html'] = 'index.php?module=blog&action=category&id=$1';
$route['dich-vu/.+-(.+).html'] = 'index.php?module=services&action=detail&id=$1';
$route['thong-tin/.+-(.+).html'] = 'index.php?module=page&action=detail&id=$1';
$route['du-an/.+-(.+).html'] = 'index.php?module=portfolios&action=detail&id=$1';
$route['gioi-thieu.html'] = 'index.php?module=page-template&action=about';
$route['doi-ngu.html'] = 'index.php?module=page-template&action=team';
$route['lien-he.html'] = 'index.php?module=page-template&action=contact';
$route['du-an.html'] = 'index.php?module=portfolios';
$route['dich-vu.html'] = 'index.php?module=services';
$route['blog.html'] = 'index.php?module=blog';
$route['blog-page-(.+).html'] = 'index.php?module=blog&page=$1';
$route['tim-kiem.html'] = 'index.php?module=search';
$route['submit-subscribe.html'] = 'index.php?module=subscribe&action=submit';
