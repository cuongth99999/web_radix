<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => 'Thiết lập Blog'
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
                <h5>Thiết lập tiêu đề</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('blog_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="blog_title" placeholder="<?php echo getOption('blog_title', 'label'); ?>..." value="<?php echo getOption('blog_title'); ?>"/>
                    <?php echo form_error('blog_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập bài viết</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('blog_per_page', 'label'); ?></label>
                    <input type="number" min="1" class="form-control" name="blog_per_page" placeholder="<?php echo getOption('blog_per_page', 'label'); ?>..." value="<?php echo getOption('blog_per_page'); ?>"/>
                    <?php echo form_error('blog_per_page', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
