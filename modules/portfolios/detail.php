<?php
if (!defined('_INCODE')) die('Access Denied...');

if (!empty(getBody()['id'])) {
    $id = getBody()['id'];

    // Thực hiện truy vấn sql với bảng portfolios
    $sql = "SELECT p.*, c.name as cate_name FROM portfolios as p INNER JOIN portfolio_categories as c ON p.portfolio_category_id = c.id 
                    WHERE p.id=$id";
    $portfolioDetail = firstRaw($sql);

    $portfolioImages = getRaw("SELECT image FROM portfolio_images WHERE portfolio_id=$id");

    if (empty($portfolioDetail)) {
        loadError();
    }
} else {
    loadError(); // Load giao diện 404
}

$data = [
    'pageTitle' => $portfolioDetail['name']
];

layout('header', 'client', $data);

$data['itemParent'] = '<li><a href="'._WEB_HOST_ROOT.'?module=portfolios">'.getOption('portfolio_title').'</a></li>';

layout('breadcrumb', 'client', $data);


?>
<!-- Services -->
<section id="services" class="services archives section">
    <div class="container">
        <h1 class="text-small"><?php echo $portfolioDetail['name']; ?></h1>

        <div class="portfolio-meta">
            Chuyên mục: <?php echo $portfolioDetail['cate_name']; ?> | Thời gian: <?php echo getDateFormat($portfolioDetail['create_at'], 'd/m/y'); ?>
        </div>
        <hr>
        <div>
            <?php echo html_entity_decode($portfolioDetail['content']); ?>
        </div>
        <div class="row" style="margin-top: 20px">
            <?php
            $checkVideo = false;
            if (!empty($portfolioDetail['video'])):
                $checkVideo = true;
            ?>
            <div class="col-6">
                <h3>Video</h3>
                <hr>
                <?php
                    $videoId = getYoutubeId($portfolioDetail['video']);
                    if (!empty($videoId)) {
                        echo '<iframe width="100%" height="315" src="https://www.youtube.com/embed/'.$videoId.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                    }
                ?>
            </div>
            <?php endif; ?>
            <?php
                if ($checkVideo) {
                    echo '<div class="col-6">';
                } else {
                    echo '<div class="col-12">';
                }
                if (!empty($portfolioImages)):
                ?>
                <h3>Ảnh dự án</h3>
                <hr>
                <div class="row">
                    <?php foreach ($portfolioImages as $item): ?>
                    <div class="col-4 mb-4">
                        <a href="<?php echo $item['image']; ?>" data-fancybox="gallery"><img src="<?php echo $item['image']; ?>" alt=""></a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php
                endif;
                echo '</div>';
            ?>
        </div>
    </div>
</section>
<!--/ End Services -->
<?php
layout('footer', 'client');
?>
