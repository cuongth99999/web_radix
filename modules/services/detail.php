<?php
if (!defined('_INCODE')) die('Access Denied...');

if (!empty(getBody()['id'])) {
    $id = getBody()['id'];

    // Thực hiện truy vấn sql với bảng services
    $sql = "SELECT * FROM services WHERE id=$id";
    $servicesDetail = firstRaw($sql);
    if (empty($servicesDetail)) {
        loadError();
    }
} else {
    loadError(); // Load giao diện 404
}

$data = [
    'pageTitle' => $servicesDetail['name']
];

layout('header', 'client', $data);

$data['itemParent'] = '<li><a href="'._WEB_HOST_ROOT.'?module=services">'.getOption('services_title').'</a></li>';

layout('breadcrumb', 'client', $data);


?>
<!-- Services -->
<section id="services" class="services archives section">
    <div class="container">
        <h1 class="text-small"><?php echo $servicesDetail['name']; ?></h1>
        <hr>
        <div class="content">
            <?php echo html_entity_decode($servicesDetail['content']); ?>
        </div>
    </div>
</section>
<!--/ End Services -->
<?php
layout('footer', 'client');
?>
