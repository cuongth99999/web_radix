<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => 'Thiết lập Footer'
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
                <h5>Thiết lập cột 1</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('footer_1_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="footer_1_title" placeholder="<?php echo getOption('footer_1_title', 'label'); ?>..." value="<?php echo getOption('footer_1_title'); ?>"/>
                    <?php echo form_error('footer_1_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('footer_1_content', 'label'); ?></label>
                    <textarea name="footer_1_content" class="form-control editor" placeholder="<?php echo getOption('footer_1_content', 'label'); ?>...""><?php echo getOption('footer_1_content'); ?></textarea>
                    <?php echo form_error('footer_1_content', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập cột 2</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('footer_2_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="footer_2_title" placeholder="<?php echo getOption('footer_2_title', 'label'); ?>..." value="<?php echo getOption('footer_2_title'); ?>"/>
                    <?php echo form_error('footer_2_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('footer_2_content', 'label'); ?></label>
                    <textarea name="footer_2_content" class="form-control editor" placeholder="<?php echo getOption('footer_2_content', 'label'); ?>...""><?php echo getOption('footer_2_content'); ?></textarea>
                    <?php echo form_error('footer_2_content', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập cột 3</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('footer_3_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="footer_3_title" placeholder="<?php echo getOption('footer_3_title', 'label'); ?>..." value="<?php echo getOption('footer_3_title'); ?>"/>
                    <?php echo form_error('footer_3_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('footer_3_twitter', 'label'); ?></label>
                    <input type="text" class="form-control" name="footer_3_twitter" placeholder="<?php echo getOption('footer_3_twitter', 'label'); ?>..." value="<?php echo getOption('footer_3_twitter'); ?>"/>
                    <?php echo form_error('footer_3_twitter', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập cột 4</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('footer_4_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="footer_4_title" placeholder="<?php echo getOption('footer_4_title', 'label'); ?>..." value="<?php echo getOption('footer_4_title'); ?>"/>
                    <?php echo form_error('footer_4_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('footer_4_content', 'label'); ?></label>
                    <textarea name="footer_4_content" class="form-control editor" placeholder="<?php echo getOption('footer_4_content', 'label'); ?>...""><?php echo getOption('footer_4_content'); ?></textarea>
                    <?php echo form_error('footer_4_content', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập bản quyền</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('footer_copyright', 'label'); ?></label>
                    <textarea name="footer_copyright" class="form-control editor" placeholder="<?php echo getOption('footer_copyright', 'label'); ?>...""><?php echo getOption('footer_copyright'); ?></textarea>
                    <?php echo form_error('footer_copyright', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
