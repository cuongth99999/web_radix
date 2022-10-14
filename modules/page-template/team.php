<?php
if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => getOption('team_title')
];
layout('header', 'client', $data);
layout('breadcrumb', 'client', $data);

$title = getOption('team_primary_title');
$titleBg = getOption('team_title_bg');
$desc = getOption('team_title_desc');

?>
<!-- Start Team -->
<section id="team" class="team section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
                        echo !empty($titleBg)?'<span class="title-bg">'.$titleBg.'</span>':false ;
                        echo !empty($title)?'<h1>'.$title.'</h1>':false ;
                        echo !empty($desc)?'<p>'.$desc.'</p>':false ;
                    ?>
                </div>
            </div>
        </div>
        <?php
        $teamContentJson = getOption('team_content');
        if (!empty($teamContentJson)) {
            $teamContentArr = json_decode($teamContentJson, true);

            if (!empty($teamContentArr)) {
                ?>
                <div class="row">
                    <?php
                        foreach ($teamContentArr as $item) {
                    ?>
                    <div class="col-lg-3 col-md-6 col-12">
                        <!-- Single Team -->
                        <div class="single-team">
                            <div class="t-head">
                                <img src="<?php echo $item['image']; ?>" alt="#">

                            </div>
                            <div class="t-bottom">
                                <p><?php echo $item['position']; ?></p>
                                <h2><?php echo $item['name']; ?></h2>
                                <ul class="t-social">
                                    <li><a href="<?php echo $item['facebook']; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="<?php echo $item['twitter']; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="<?php echo $item['linkedin']; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a href="<?php echo $item['behance']; ?>" target="_blank"><i class="fa fa-behance"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- End Single Team -->
                    </div>
                    <?php
                        }
                    ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
</section>
<!--/ End Team -->
<?php
layout('footer', 'client');
?>
