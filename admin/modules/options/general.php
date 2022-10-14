<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => 'Thiết lập chung'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

updateOptions();

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');

?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="" method="post">
                <?php
                getMsg($msg, $msgType);
                ?>
                <h5>Thông tin chung</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('general_sitename', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_sitename" placeholder="<?php echo getOption('general_sitename', 'label'); ?>..." value="<?php echo getOption('general_sitename'); ?>"/>
                    <?php echo form_error('general_sitename', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('general_sitedesc', 'label'); ?></label>
                    <textarea name="general_sitedesc" class="form-control" placeholder="<?php echo getOption('general_sitedesc', 'label'); ?>...""><?php echo getOption('general_sitedesc'); ?></textarea>
                    <?php echo form_error('general_sitedesc', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thông tin liên hệ</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('general_hotline', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_hotline" placeholder="Hotline..." value="<?php echo getOption('general_hotline'); ?>"/>
                    <?php echo form_error('general_hotline', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('general_email', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_email" placeholder="Email..." value="<?php echo getOption('general_email'); ?>"/>
                    <?php echo form_error('general_email', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('general_adress', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_adress" placeholder="<?php echo getOption('general_adress', 'label'); ?>..." value="<?php echo getOption('general_adress'); ?>"/>
                    <?php echo form_error('general_adress', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('general_time', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_time" placeholder="Thời gian làm việc..." value="<?php echo getOption('general_time'); ?>"/>
                    <?php echo form_error('general_time', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('general_time', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_time" placeholder="Thời gian làm việc..." value="<?php echo getOption('general_time'); ?>"/>
                    <?php echo form_error('general_time', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('general_facebook', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_facebook" placeholder="<?php echo getOption('general_facebook', 'label'); ?>..." value="<?php echo getOption('general_facebook'); ?>"/>
                    <?php echo form_error('general_facebook', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('general_twitter', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_twitter" placeholder="<?php echo getOption('general_twitter', 'label'); ?>..." value="<?php echo getOption('general_twitter'); ?>"/>
                    <?php echo form_error('general_twitter', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('general_linkedin', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_linkedin" placeholder="<?php echo getOption('general_linkedin', 'label'); ?>..." value="<?php echo getOption('general_linkedin'); ?>"/>
                    <?php echo form_error('general_linkedin', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('general_behance', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_behance" placeholder="<?php echo getOption('general_behance', 'label'); ?>..." value="<?php echo getOption('general_behance'); ?>"/>
                    <?php echo form_error('general_behance', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('general_youtube', 'label'); ?></label>
                    <input type="text" class="form-control" name="general_youtube" placeholder="<?php echo getOption('general_youtube', 'label'); ?>..." value="<?php echo getOption('general_youtube'); ?>"/>
                    <?php echo form_error('general_youtube', $errors, '<span class="error">', '</span>'); ?>
                </div>


                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
