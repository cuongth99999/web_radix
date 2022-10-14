<?php
$title = getOption('home_partner_title');
$titleBg = getOption('home_partner_title_bg');
$desc = getOption('home_partner_desc');
$partnerJson = getOption('home_partner_content');
$partnerArr = [];
if (!empty($partnerJson)) {
    $partnerArr = json_decode($partnerJson, true);
}
?>
<!-- Partners -->
<section id="partners" class="partners section">
    <div class="container">
        <div class="row">
            <div class="col-12 wow fadeInUp">
                <div class="section-title">
                    <?php
                        echo !empty($titleBg)?'<span class="title-bg">'.$titleBg.'</span>':false ;
                        echo !empty($title)?'<h1>'.$title.'</h1>':false ;
                        echo !empty($desc)?'<p>'.$desc.'</p>':false ;
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="partners-inner">
                    <div class="row no-gutters">
                        <?php
                            if (!empty($partnerArr)):
                                foreach ($partnerArr as $item):
                        ?>
                        <!-- Single Partner -->
                        <div class="col-lg-2 col-md-3 col-12">
                            <div class="single-partner">
                                <a href="<?php echo $item['link']; ?>" target="_blank"><img src="<?php echo $item['logo']; ?>" alt="#"></a>
                            </div>
                        </div>
                        <!--/ End Single Partner -->
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ End Partners -->