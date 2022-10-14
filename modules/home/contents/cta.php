<!-- Call To Action -->
<section class="call-to-action section" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12 wow fadeInUp">
                <div class="call-to-main">
                    <?php
                        echo html_entity_decode(getOption('home_cta_content'));

                        $buttonLink = getOption('home_cta_button_link');
                        if (!empty($buttonLink)) {
                            ?>
                            <a href="<?php echo $buttonLink; ?>" class="btn"><?php echo getOption('home_cta_button_text'); ?></a>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ End Call To Action -->
