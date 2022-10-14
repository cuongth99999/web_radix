<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => 'Thiết lập liên hệ'
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
                <h5>Thông tin tiêu đề</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('contact_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="contact_title" placeholder="<?php echo getOption('contact_title', 'label'); ?>..." value="<?php echo getOption('contact_title'); ?>"/>
                    <?php echo form_error('contact_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập chung</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('contact_primary_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="contact_primary_title" placeholder="<?php echo getOption('contact_primary_title', 'label'); ?>..." value="<?php echo getOption('contact_primary_title'); ?>"/>
                    <?php echo form_error('contact_primary_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('contact_title_bg', 'label'); ?></label>
                    <input type="text" class="form-control" name="contact_title_bg" placeholder="<?php echo getOption('contact_title_bg', 'label'); ?>..." value="<?php echo getOption('contact_title_bg'); ?>"/>
                    <?php echo form_error('contact_title_bg', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('contact_title_desc', 'label'); ?></label>
                    <textarea name="contact_title_desc" class="form-control" placeholder="<?php echo getOption('contact_title_desc', 'label'); ?>...""><?php echo getOption('contact_title_desc'); ?></textarea>
                    <?php echo form_error('contact_title_desc', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
