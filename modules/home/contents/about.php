<?php
$homeAboutJson = getOption('home_about');
$homeAboutArr = [];
$homeAboutInfo = [];
$homeAboutSkill = [];
if (!empty($homeAboutJson)) {
    $homeAboutArr = json_decode($homeAboutJson, true);
    $homeAboutInfo = json_decode($homeAboutArr['information'], true);
    $homeAboutSkill = json_decode($homeAboutArr['skill'], true);
}
?>
<!-- About Us -->
<section class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title wow fadeInUp">
                    <span class="title-bg"><?php echo !empty($homeAboutInfo['title_bg'])?$homeAboutInfo['title_bg']:false; ?></span>
                    <?php
                        if (!empty($homeAboutInfo['desc'])) {
                            echo html_entity_decode($homeAboutInfo['desc']);
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-12 wow fadeInLeft" data-wow-delay="0.6s">
                <?php if (!empty($homeAboutInfo['image']) && $homeAboutInfo['video']): ?>
                <!-- Video -->
                <div class="about-video">
                    <div class="single-video overlay">
                        <a href="<?php echo $homeAboutInfo['video']; ?>" class="video-popup mfp-fade"><i class="fa fa-play"></i></a>
                        <img src="<?php echo $homeAboutInfo['image']; ?>" alt="#">
                    </div>
                </div>
                <!--/ End Video -->
                <?php endif; ?>
            </div>
            <div class="col-lg-6 col-12 wow fadeInRight" data-wow-delay="0.8s">
                <!-- About Content -->
                <div class="about-content">
                   <?php
                    if (!empty($homeAboutInfo['content'])) {
                        echo html_entity_decode($homeAboutInfo['content']);
                    }
                   ?>
                </div>
                <!--/ End About Content -->
            </div>
        </div>
        <?php if (!empty($homeAboutSkill)): ?>
        <div class="row">
            <div class="col-12">
                <div class="progress-main">
                    <div class="row">
                        <?php foreach ($homeAboutSkill as $item): ?>
                        <div class="col-lg-6 col-md-6 col-12 wow fadeInUp" data-wow-delay="0.4s">
                            <!-- Single Skill -->
                            <div class="single-progress">
                                <h4><?php echo $item['name'] ?></h4>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $item['value'] ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="percent"><?php echo $item['value'] ?>%</span></div>
                                </div>
                            </div>
                            <!--/ End Single Skill -->
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<!--/ End About Us -->
