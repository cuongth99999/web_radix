
<!-- Services -->
<section id="services" class="services section">
    <div class="container">
        <div class="row">
            <div class="col-12 wow fadeInUp">
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
            <div class="col-12">
                <div class="service-slider">
                    <?php
                        foreach ($serviceLists as $item):
                    ?>
                    <!-- Single Service -->
                    <div class="single-service">
                        <?php echo html_entity_decode($item['icon']); ?>
                        <h2><a href="<?php echo getLinkModule('services', $item['id']); ?>"><?php echo $item['name']; ?></a></h2>
                        <p><?php echo $item['description']; ?></p>
                    </div>
                    <!-- End Single Service -->
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<!--/ End Services -->
