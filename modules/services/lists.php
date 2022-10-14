<?php
if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => getOption('services_title')
];
layout('header', 'client', $data);
layout('breadcrumb', 'client', $data);
?>
<!-- Services -->
<section id="services" class="services archives section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <span class="title-bg"><?php echo getOption('home_service_title_bg'); ?></span>
                    <h1><?php echo getOption('home_service_title'); ?></h1>
                    <p><?php echo getOption('home_service_desc'); ?><p>
                </div>
            </div>
        </div>
        <?php
        $serviceLists = getRaw("SELECT * FROM services");
        if (!empty($serviceLists)):
        ?>
        <div class="row">
            <?php
                foreach ($serviceLists as $item):
            ?>
            <div class="col-lg-4 col-md-6 col-12">
                <!-- Single Service -->
                <div class="single-service">
                    <?php echo html_entity_decode($item['icon']); ?>
                    <h2><a href="<?php echo getLinkModule('services', $item['id']); ?>"><?php echo $item['name']; ?></a></h2>
                    <p><?php echo $item['description']; ?></p>
                </div>
                <!-- End Single Service -->
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <div class="alert alert-info text-center">Không có dịch vụ</div>
        <?php endif; ?>
    </div>
</section>
<!--/ End Services -->
<?php
layout('footer', 'client');
?>
