<?php
if (!defined('_INCODE')) die('Access Denied...');

if (!empty(getBody()['id'])) {
    $pageId = getBody()['id'];
    $pageDetail = firstRaw("SELECT * FROM pages WHERE id=$pageId");

    if (empty($pageDetail)) {
        loadError();
    }
} else {
    loadError();
}

$data = [
    'pageTitle' => $pageDetail['title']
];

layout('header', 'client', $data);

layout('breadcrumb', 'client', $data);

?>
<!-- Blogs Area -->
<section class="blogs-main archives single section">
    <div class="container">
        <h1 class="text-small"><?php echo $pageDetail['title']; ?></h1>
        <hr>
        <div>
            <?php echo html_entity_decode($pageDetail['content']); ?>
        </div>
    </div>
</section>
<!--/ End Blogs Area -->
<?php
layout('footer', 'client');
?>
