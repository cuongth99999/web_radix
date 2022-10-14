<?php
if (!defined('_INCODE')) die('Access Denied...');

$menuDataArr = [];
$menuJson = html_entity_decode(getOption('menu'));
if (!empty($menuJson)) {
    $menuDataArr = json_decode($menuJson, true);
}

?>
<!DOCTYPE html>
<html class="no-js" lang="zxx">
<head>
    <!-- Meta tag -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="Radix" content="Responsive Multipurpose Business Template">
    <meta name='copyright' content='Radix'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Title Tag -->
    <title><?php echo !empty($data['pageTitle'])?$data['pageTitle']:false; ?> - <?php echo getOption('general_sitename'); ?></title>

    <?php
        $faviconUrl = getOption('header_favicon');
        if (!empty($faviconUrl)):
    ?>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $faviconUrl; ?>">
    <?php endif; ?>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,700,800" rel="stylesheet">

    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/font-awesome.min.css">
    <!-- Slick Nav CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/slicknav.min.css">
    <!-- Cube Portfolio CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/cubeportfolio.min.css">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/magnific-popup.min.css">
    <!-- Fancy Box CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/jquery.fancybox.min.css">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/niceselect.css">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/owl.theme.default.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/owl.carousel.min.css">
    <!-- Slick Slider CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/slickslider.min.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/animate.min.css">

    <!-- Radix StyleShet CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/reset.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/style.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/responsive.css">

    <!-- Radix Color CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/color/color1.css">

    <?php
        head();
    ?>
</head>
<body>

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <div class="single-loader one"></div>
        <div class="single-loader two"></div>
        <div class="single-loader three"></div>
        <div class="single-loader four"></div>
        <div class="single-loader five"></div>
        <div class="single-loader six"></div>
        <div class="single-loader seven"></div>
        <div class="single-loader eight"></div>
        <div class="single-loader nine"></div>
    </div>
</div>
<!-- End Preloader -->

<!-- Start Header -->
<header id="header" class="header">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <!-- Contact -->
                    <ul class="contact">
                        <li><i class="fa fa-headphones"></i> <?php echo getOption('general_hotline'); ?></li>
                        <li><i class="fa fa-envelope"></i> <a href="mailto:<?php echo getOption('general_email'); ?>"><?php echo getOption('general_email'); ?></a></li>
                        <li><i class="fa fa-clock-o"></i><?php echo getOption('general_time'); ?></li>
                    </ul>
                    <!--/ End Contact -->
                </div>
                <div class="col-lg-6 col-12">
                    <div class="topbar-right">
                        <!-- Search Form -->
                        <div class="search-form active">
                            <a class="icon" href="#"><i class="fa fa-search"></i></a>
                            <form class="form" method="get" action="<?php echo _WEB_HOST_ROOT.'/tim-kiem.html'; ?>">
                                <input placeholder="<?php echo getOption('header_search_placeholder'); ?>" type="search"
                                value="<?php echo !empty(getBody()['keyword'])?getBody()['keyword']:false; ?>" name="keyword">
<!--                                <input type="hidden" name="module" value="search">-->
                            </form>
                        </div>
                        <!--/ End Search Form -->
                        <!-- Social -->
                        <ul class="social">
                            <li><a target="_blank" href="<?php echo getOption('general_twitter'); ?>"><i class="fa fa-twitter"></i></a></li>
                            <li><a target="_blank" href="<?php echo getOption('general_facebook'); ?>"><i class="fa fa-facebook"></i></a></li>
                            <li><a target="_blank" href="<?php echo getOption('general_linkedin'); ?>"><i class="fa fa-linkedin"></i></a></li>
                            <li><a target="_blank" href="<?php echo getOption('general_behance'); ?>"><i class="fa fa-behance"></i></a></li>
                            <li><a target="_blank" href="<?php echo getOption('general_youtube'); ?>"><i class="fa fa-youtube"></i></a></li>
                        </ul>
                        <!--/ End Social -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Topbar -->
    <!-- Middle Bar -->
    <div class="middle-bar">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-12">
                    <?php
                        $logo = getOption('header_logo');
                    ?>
                    <!-- Logo -->
                    <div class="logo">
                        <a href="<?php echo _WEB_HOST_ROOT; ?>">
                            <?php if (!empty($logo)): ?>
                            <img src="<?php echo $logo ?>" alt="logo"></a>
                            <?php else: ?>
                                <h1 style="margin-top: 10px"><?php echo getOption('general_sitename')?></h1>
                            <?php endif; ?>
                    </div>
                    <div class="link"><a href="<?php echo _WEB_HOST_ROOT; ?>"><img src="<?php echo $logo ?>" style="height: 45px; width: auto"></a></a></div>
                    <!--/ End Logo -->
                    <button class="mobile-arrow"><i class="fa fa-bars"></i></button>
                    <div class="mobile-menu"></div>
                </div>
                <div class="col-lg-10 col-12">
                    <!-- Main Menu -->
                    <div class="mainmenu">
                        <nav class="navigation">
                            <?php
                                getMenu($menuDataArr);
                            ?>
<!--                            <ul class="nav menu">-->
<!--                                <li class="active"><a href="--><?php //echo _WEB_HOST_ROOT; ?><!--">Home</a></li>-->
<!--                                <li><a href="#">Pages<i class="fa fa-caret-down"></i></a>-->
<!--                                    <ul class="dropdown">-->
<!--                                        <li><a href="--><?php //echo _WEB_HOST_ROOT.'?module=page-template&action=about'; ?><!--">About Us</a></li>-->
<!--                                        <li><a href="--><?php //echo _WEB_HOST_ROOT.'?module=page-template&action=team'; ?><!--">Our Team</a></li>-->
<!--<!--                                        <li><a href="pricing.html">Pricing</a></li>-->-->
<!--                                    </ul>-->
<!--                                </li>-->
<!--                                <li><a href="--><?php //echo _WEB_HOST_ROOT.'?module=services'; ?><!--">Services</a></li>-->
<!--                                <li><a href="--><?php //echo _WEB_HOST_ROOT.'?module=portfolios'; ?><!--">Portfolio</a></li>-->
<!--                                <li><a href="#">Blogs<i class="fa fa-caret-down"></i></a>-->
<!--                                    <ul class="dropdown">-->
<!--                                        <li><a href="blog.html">Blog layout</a></li>-->
<!--                                        <li><a href="blog-single.html">Blog Single</a></li>-->
<!--                                    </ul>-->
<!--                                </li>-->
<!--                                <li><a href="contact.html">Contact</a></li>-->
<!--                            </ul>-->
                        </nav>
                        <!-- Button -->
                        <div class="button">
                            <a href="<?php echo getOption('header_quote_link'); ?>" class="btn"><?php echo getOption('header_quote_text'); ?></a>
                        </div>
                        <!--/ End Button -->
                    </div>
                    <!--/ End Main Menu -->
                </div>
            </div>
        </div>
    </div>
    <!--/ End Middle Bar -->
</header>
<!--/ End Header -->
