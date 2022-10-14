<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => 'Thiết lập Team'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

if (isPost()) {
    $teamContentJson = '';
    if (!empty(getBody()['team_content'])) {
        $teamContent = getBody()['team_content'];

        $teamContentArr = [];

        if (!empty($teamContent['name'])) {
            foreach ($teamContent['name'] as $key => $value) {
                $teamContentArr[] = [
                        'name' => $value,
                        'position' => $teamContent['position'][$key],
                        'image' => $teamContent['image'][$key],
                        'facebook' => $teamContent['facebook'][$key],
                        'twitter' => $teamContent['twitter'][$key],
                        'linkedin' => $teamContent['linkedin'][$key],
                        'behance' => $teamContent['behance'][$key]
                ];
            }
        }

        $teamContentJson = json_encode($teamContentArr);
    }

    $data = [
        'team_content' => $teamContentJson
    ];

    updateOptions($data);
}

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
                    <label for=""><?php echo getOption('team_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="team_title" placeholder="<?php echo getOption('team_title', 'label'); ?>..." value="<?php echo getOption('team_title'); ?>"/>
                    <?php echo form_error('team_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập chung</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('team_primary_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="team_primary_title" placeholder="<?php echo getOption('team_primary_title', 'label'); ?>..." value="<?php echo getOption('team_primary_title'); ?>"/>
                    <?php echo form_error('team_primary_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('team_title_bg', 'label'); ?></label>
                    <input type="text" class="form-control" name="team_title_bg" placeholder="<?php echo getOption('team_title_bg', 'label'); ?>..." value="<?php echo getOption('team_title_bg'); ?>"/>
                    <?php echo form_error('team_title_bg', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('team_title_desc', 'label'); ?></label>
                    <textarea name="team_title_desc" class="form-control" placeholder="<?php echo getOption('team_title_desc', 'label'); ?>...""><?php echo getOption('team_title_desc'); ?></textarea>
                    <?php echo form_error('team_title_desc', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Danh sách đội ngũ</h5>
                <div class="team-wrapper">
                    <?php
                    $teamContentJson = getOption('team_content');
                    if (!empty($teamContentJson)) {
                        $teamContentArr = json_decode($teamContentJson, true);

                        if (!empty($teamContentArr)) {
                            foreach ($teamContentArr as $item) {
                                ?>
                                <div class="team-item">
                                    <div class="row">
                                        <div class="col-11">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="">Tên</label>
                                                        <input type="text" class="form-control" name="team_content[name][]" placeholder="Tên..."
                                                               value="<?php echo $item['name']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="">Chức vụ</label>
                                                        <input type="text" class="form-control" name="team_content[position][]" placeholder="Chức vụ..."
                                                               value="<?php echo $item['position']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">Ảnh</label>
                                                        <div class="row ckfinder-group">
                                                            <div class="col-10">
                                                                <input type="text" class="form-control image-render" name="team_content[image][]"
                                                                       placeholder="Đường dẫn ảnh..."
                                                                       value="<?php echo $item['image']; ?>">
                                                            </div>
                                                            <div class="col-2">
                                                                <button type="button" class="btn btn-success btn-block choose-image"><i class="fa fa-upload" aria-hidden="true"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="">Facebook</label>
                                                        <input type="text" class="form-control" name="team_content[facebook][]" placeholder="Facebook..."
                                                               value="<?php echo $item['facebook']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="">Twitter</label>
                                                        <input type="text" class="form-control" name="team_content[twitter][]" placeholder="Twitter..."
                                                               value="<?php echo $item['twitter']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="">LinkedIn</label>
                                                        <input type="text" class="form-control" name="team_content[linkedin][]" placeholder="LinkedIn..."
                                                               value="<?php echo $item['linkedin']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="">Behance</label>
                                                        <input type="text" class="form-control" name="team_content[behance][]" placeholder="Behance..."
                                                               value="<?php echo $item['behance']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <a href="" class="btn btn-danger btn-sm btn-block remove">&times;</a>
                                        </div>
                                    </div>
                                </div><!--End .team-item-->
                                <?php
                            }
                        }
                    }
                    ?>
                </div><!--End .team-wrapper-->
                <p><button type="button" class="btn btn-warning btn-sm add-team">Thêm thành viên</button></p>

                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
