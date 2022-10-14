<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => 'Thiết lập trang chủ'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

//updateOptions();

if (isPost()) {

    $homeSlideJson = '';

    if (!empty(getBody()['home_slide'])) {

        $homeSlide = getBody()['home_slide'];

        $homeSlideArr = [];

        if (!empty($homeSlide['slide_title'])) {
            foreach ($homeSlide['slide_title'] as $key => $value) {
                $homeSlideArr[] = [
                   'slide_title' => $value,
                   'slide_button_text' => isset($homeSlide['slide_button_text'][$key])?$homeSlide['slide_button_text'][$key]:'',
                    'slide_button_link' => isset($homeSlide['slide_button_link'][$key])?$homeSlide['slide_button_link'][$key]:'',
                    'slide_video' => isset($homeSlide['slide_video'][$key])?$homeSlide['slide_video'][$key]:'',
                    'slide_image_1' => isset($homeSlide['slide_image_1'][$key])?$homeSlide['slide_image_1'][$key]:'',
                    'slide_image_2' => isset($homeSlide['slide_image_2'][$key])?$homeSlide['slide_image_2'][$key]:'',
                    'slide_desc' => isset($homeSlide['slide_desc'][$key])?$homeSlide['slide_desc'][$key]:'',
                    'slide_bg' => isset($homeSlide['slide_bg'][$key])?$homeSlide['slide_bg'][$key]:'',
                    'slide_position' => isset($homeSlide['slide_position'][$key])?$homeSlide['slide_position'][$key]:'left'
                ];
            }
        }

        // Chuyển mảng thành chuỗi json
        $homeSlideJson = json_encode($homeSlideArr);

    }
    /*
     * Cấu trúc mảng cần chuyển:
     * [0] => [
     *  'slide_title' => 'Tiêu đề 1',
     *  'slide_button_text' => 'Xem thêm',
     *  'slide_button_link' => '#'
     * ]
     * */

    $homeAbout = [];
    if (!empty(getBody()['home_about'])) {
        $homeAbout['information'] = json_encode(getBody()['home_about']);
    }

    $skillJson = '';

    if (!empty(getBody()['home_about']['skill'])) {
        $skillArr = [];
        if (!empty(getBody()['home_about']['skill']['name'])) {
            foreach (getBody()['home_about']['skill']['name'] as $key => $value) {
                $skillArr[] = [
                    'name' => $value,
                    'value' => getBody()['home_about']['skill']['value'][$key]
                ];
            }

            $skillJson = json_encode($skillArr);
        }

    }

    $homeAbout['skill'] = $skillJson;

    $homeAboutJson = json_encode($homeAbout);

    // Xử lý update danh sách đối tác
    $partnerJson = '';

    if (!empty(getBody()['home_partner_content'])) {
        $partnerArr = [];
        if (!empty(getBody()['home_partner_content']['logo'])) {
            foreach (getBody()['home_partner_content']['logo'] as $key => $value) {
                $partnerArr[] = [
                    'logo' => $value,
                    'link' => getBody()['home_partner_content']['link'][$key]
                ];
            }

            $partnerJson = json_encode($partnerArr);
        }

    }

    $data = [
      'home_slide' => $homeSlideJson,
      'home_about' => $homeAboutJson,
       'home_partner_content' => $partnerJson
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
                <h5>Thiết lập slide</h5>

                <div class="slide-wrapper">
                    <?php
                        $homeSlideJson = getOption('home_slide');
                        if (!empty($homeSlideJson)) {
                            $homeSlideArr = json_decode($homeSlideJson, true);
                            if (!empty($homeSlideArr)) {
                                foreach ($homeSlideArr as $item) {
                                    ?>
                                    <div class="slide-item">
                                        <div class="row">
                                            <div class="col-11">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="">Tiêu đề</label>
                                                            <input type="text" class="form-control" name="home_slide[slide_title][]" placeholder="Tiêu đề slide..."
                                                            value="<?php echo $item['slide_title']?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="">Nút xem thêm</label>
                                                            <input type="text" class="form-control" name="home_slide[slide_button_text][]" placeholder="Chữ của nút..."
                                                                   value="<?php echo $item['slide_button_text']?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="">Link xem thêm</label>
                                                            <input type="text" class="form-control" name="home_slide[slide_button_link][]" placeholder="Link của nút..."
                                                                   value="<?php echo $item['slide_button_link']?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="">Link Youtube</label>
                                                            <input type="text" class="form-control" name="home_slide[slide_video][]" placeholder="Link video youtube..."
                                                                   value="<?php echo $item['slide_video']?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="">Ảnh 1</label>
                                                            <div class="row ckfinder-group">
                                                                <div class="col-10">
                                                                    <input type="text" class="form-control image-render" name="home_slide[slide_image_1][]"
                                                                           placeholder="Đường dẫn ảnh..."
                                                                           value="<?php echo $item['slide_image_1']?>">
                                                                </div>
                                                                <div class="col-2">
                                                                    <button type="button" class="btn btn-success btn-block choose-image"><i class="fa fa-upload" aria-hidden="true"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="">Ảnh 2</label>
                                                            <div class="row ckfinder-group">
                                                                <div class="col-10">
                                                                    <input type="text" class="form-control image-render" name="home_slide[slide_image_2][]"
                                                                           placeholder="Đường dẫn ảnh..."
                                                                           value="<?php echo $item['slide_image_2']?>">
                                                                </div>
                                                                <div class="col-2">
                                                                    <button type="button" class="btn btn-success btn-block choose-image"><i class="fa fa-upload" aria-hidden="true"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="">Ảnh nền</label>
                                                            <div class="row ckfinder-group">
                                                                <div class="col-10">
                                                                    <input type="text" class="form-control image-render" name="home_slide[slide_bg][]"
                                                                           placeholder="Đường dẫn ảnh..."
                                                                           value="<?php echo $item['slide_bg']?>">
                                                                </div>
                                                                <div class="col-2">
                                                                    <button type="button" class="btn btn-success btn-block choose-image"><i class="fa fa-upload" aria-hidden="true"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="">Vị trí slide</label>
                                                            <select name="home_slide[slide_position][]" class="form-control">
                                                                <option value="left" <?php echo $item['slide_position']=='left'?'selected':false; ?>>Trái</option>
                                                                <option value="right" <?php echo $item['slide_position']=='right'?'selected':false; ?>>Phải</option>
                                                                <option value="center" <?php echo $item['slide_position']=='center'?'selected':false; ?>>Giữa</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">Mô tả</label>
                                                            <textarea name="home_slide[slide_desc][]" class="form-control" placeholder="Mô tả slide..."><?php echo $item['slide_desc']?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-1">
                                                <a href="" class="btn btn-danger btn-sm btn-block remove">&times;</a>
                                            </div>
                                        </div>
                                    </div><!--End .slide-item-->
                                    <?php

                                }
                            }
                        }
                    ?>
                </div><!--End .slide-wrapper-->

                <p><button type="button" class="btn btn-warning btn-sm add-slide">Thêm slide</button></p>

                <h5>Thiết lập giới thiệu</h5>

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
                
                <div class="form-group">
                    <label for="">Tiêu đề nền</label>
                    <input type="text" class="form-control" name="home_about[title_bg]" placeholder="Tiêu đề..."
                            value="<?php echo (!empty($homeAboutInfo['title_bg']))?$homeAboutInfo['title_bg']:false; ?>">
                </div>

                <div class="form-group">
                    <label for="">Mô tả</label>
                    <textarea name="home_about[desc]" class="editor"><?php echo (!empty($homeAboutInfo['desc']))?$homeAboutInfo['desc']:false; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="">Hình ảnh</label>
                    <div class="row ckfinder-group">
                        <div class="col-10">
                            <input type="text" class="form-control image-render" name="home_about[image]"
                                   placeholder="Đường dẫn ảnh..."
                                   value="<?php echo (!empty($homeAboutInfo['image']))?$homeAboutInfo['image']:false; ?>">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-success btn-block choose-image"><i class="fa fa-upload" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Video</label>
                    <input type="text" class="form-control" name="home_about[video]" placeholder="Link video..."
                           value="<?php echo (!empty($homeAboutInfo['video']))?$homeAboutInfo['video']:false; ?>">
                </div>

                <div class="form-group">
                    <label for="">Nội dung giới thiệu</label>
                    <textarea name="home_about[content]" class="editor"><?php echo (!empty($homeAboutInfo['content']))?$homeAboutInfo['content']:false; ?></textarea>
                </div>

                <h5>Thiết lập năng lực</h5>
                <div class="skill-wrapper">
                    <?php
                        if (!empty($homeAboutSkill)):
                            foreach ($homeAboutSkill as $item):
                    ?>
                    <div class="skill-item">
                        <div class="row">
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Tên năng lực</label>
                                            <input type="text" class="form-control" name="home_about[skill][name][]" placeholder="Tên năng lực..."
                                                    value="<?php echo $item['name'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Giá trị</label>
                                            <input type="text" class="ranger" class="form-control" name="home_about[skill][value][]"
                                                   value="<?php echo $item['value'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1">
                                <a href="" class="btn btn-danger btn-sm btn-block remove">&times;</a>
                            </div>
                        </div>
                    </div><!--End . skill-item-->
                    <?php
                            endforeach;
                            endif;
                    ?>
                </div><!--End .skill-wrapper-->
                <p><button type="button" class="btn btn-warning btn-sm add-skill">Thêm năng lực</button></p>

                <h5>Thiết lập dịch vụ</h5>
                <div class="form-group">
                    <label for=""><?php echo getOption('home_service_title_bg', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_service_title_bg" placeholder="<?php echo getOption('home_service_title_bg', 'label'); ?>..." value="<?php echo getOption('home_service_title_bg'); ?>"/>
                    <?php echo form_error('home_service_title_bg', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_service_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_service_title" placeholder="<?php echo getOption('home_service_title', 'label'); ?>..." value="<?php echo getOption('home_service_title'); ?>"/>
                    <?php echo form_error('home_service_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_service_desc', 'label'); ?></label>
                    <textarea name="home_service_desc" class="form-control" placeholder="<?php echo getOption('home_service_desc', 'label'); ?>...""><?php echo getOption('home_service_desc'); ?></textarea>
                    <?php echo form_error('home_service_desc', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập thành tựu</h5>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_fact_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_fact_title" placeholder="<?php echo getOption('home_fact_title', 'label'); ?>..." value="<?php echo getOption('home_fact_title'); ?>"/>
                    <?php echo form_error('home_fact_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_fact_sub_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_fact_sub_title" placeholder="<?php echo getOption('home_fact_sub_title', 'label'); ?>..." value="<?php echo getOption('home_fact_sub_title'); ?>"/>
                    <?php echo form_error('home_fact_sub_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_fact_desc', 'label'); ?></label>
                    <textarea name="home_fact_desc" class="form-control" placeholder="<?php echo getOption('home_fact_desc', 'label'); ?>...""><?php echo getOption('home_fact_desc'); ?></textarea>
                    <?php echo form_error('home_fact_desc', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_fact_button_text', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_fact_button_text" placeholder="<?php echo getOption('home_fact_button_text', 'label'); ?>..." value="<?php echo getOption('home_fact_button_text'); ?>"/>
                    <?php echo form_error('home_fact_button_text', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_fact_button_link', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_fact_button_link" placeholder="<?php echo getOption('home_fact_button_link', 'label'); ?>..." value="<?php echo getOption('home_fact_button_link'); ?>"/>
                    <?php echo form_error('home_fact_button_link', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_fact_year_number', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_fact_year_number" placeholder="<?php echo getOption('home_fact_year_number', 'label'); ?>..." value="<?php echo getOption('home_fact_year_number'); ?>"/>
                    <?php echo form_error('home_fact_year_number', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_fact_project_number', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_fact_project_number" placeholder="<?php echo getOption('home_fact_project_number', 'label'); ?>..." value="<?php echo getOption('home_fact_project_number'); ?>"/>
                    <?php echo form_error('home_fact_project_number', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_fact_earn_number', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_fact_earn_number" placeholder="<?php echo getOption('home_fact_earn_number', 'label'); ?>..." value="<?php echo getOption('home_fact_earn_number'); ?>"/>
                    <?php echo form_error('home_fact_earn_number', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_fact_award_number', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_fact_award_number" placeholder="<?php echo getOption('home_fact_award_number', 'label'); ?>..." value="<?php echo getOption('home_fact_award_number'); ?>"/>
                    <?php echo form_error('home_fact_award_number', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập dự án</h5>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_portfolio_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_portfolio_title" placeholder="<?php echo getOption('home_portfolio_title', 'label'); ?>..." value="<?php echo getOption('home_portfolio_title'); ?>"/>
                    <?php echo form_error('home_portfolio_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_portfolio_title_bg', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_portfolio_title_bg" placeholder="<?php echo getOption('home_portfolio_title_bg', 'label'); ?>..." value="<?php echo getOption('home_portfolio_title_bg'); ?>"/>
                    <?php echo form_error('home_portfolio_title_bg', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_portfolio_desc', 'label'); ?></label>
                    <textarea name="home_portfolio_desc" class="form-control" placeholder="<?php echo getOption('home_portfolio_desc', 'label'); ?>...""><?php echo getOption('home_portfolio_desc'); ?></textarea>
                    <?php echo form_error('home_portfolio_desc', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_portfolio_more_text', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_portfolio_more_text" placeholder="<?php echo getOption('home_portfolio_more_text', 'label'); ?>..." value="<?php echo getOption('home_portfolio_more_text'); ?>"/>
                    <?php echo form_error('home_portfolio_more_text', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_portfolio_more_link', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_portfolio_more_link" placeholder="<?php echo getOption('home_portfolio_more_link', 'label'); ?>..." value="<?php echo getOption('home_portfolio_more_link'); ?>"/>
                    <?php echo form_error('home_portfolio_more_link', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập kêu gọi hành động</h5>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_cta_content', 'label'); ?></label>
                    <textarea name="home_cta_content" class="form-control editor" placeholder="<?php echo getOption('home_cta_content', 'label'); ?>...""><?php echo getOption('home_cta_content'); ?></textarea>
                    <?php echo form_error('home_cta_content', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_cta_button_text', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_cta_button_text" placeholder="<?php echo getOption('home_cta_button_text', 'label'); ?>..." value="<?php echo getOption('home_cta_button_text'); ?>"/>
                    <?php echo form_error('home_cta_button_text', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_cta_button_link', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_cta_button_link" placeholder="<?php echo getOption('home_cta_button_link', 'label'); ?>..." value="<?php echo getOption('home_cta_button_link'); ?>"/>
                    <?php echo form_error('home_cta_button_link', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập Blog</h5>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_blog_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_blog_title" placeholder="<?php echo getOption('home_blog_title', 'label'); ?>..." value="<?php echo getOption('home_blog_title'); ?>"/>
                    <?php echo form_error('home_blog_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_blog_title_bg', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_blog_title_bg" placeholder="<?php echo getOption('home_blog_title_bg', 'label'); ?>..." value="<?php echo getOption('home_blog_title_bg'); ?>"/>
                    <?php echo form_error('home_blog_title_bg', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_blog_desc', 'label'); ?></label>
                    <textarea name="home_blog_desc" class="form-control" placeholder="<?php echo getOption('home_blog_desc', 'label'); ?>...""><?php echo getOption('home_blog_desc'); ?></textarea>
                    <?php echo form_error('home_blog_desc', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Thiết lập đối tác</h5>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_partner_title', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_partner_title" placeholder="<?php echo getOption('home_partner_title', 'label'); ?>..." value="<?php echo getOption('home_partner_title'); ?>"/>
                    <?php echo form_error('home_partner_title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_partner_title_bg', 'label'); ?></label>
                    <input type="text" class="form-control" name="home_partner_title_bg" placeholder="<?php echo getOption('home_partner_title_bg', 'label'); ?>..." value="<?php echo getOption('home_partner_title_bg'); ?>"/>
                    <?php echo form_error('home_partner_title_bg', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for=""><?php echo getOption('home_partner_desc', 'label'); ?></label>
                    <textarea name="home_partner_desc" class="form-control" placeholder="<?php echo getOption('home_partner_desc', 'label'); ?>...""><?php echo getOption('home_partner_desc'); ?></textarea>
                    <?php echo form_error('home_partner_desc', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <h5>Danh sách đối tác</h5>
                <div class="partner-wrapper">
                    <?php
                    $partnerJson = getOption('home_partner_content');
                    if (!empty($partnerJson)):
                        $partnerArr = json_decode($partnerJson, true);
                        foreach ($partnerArr as $item):
                    ?>
                    <div class="partner-item">
                        <div class="row">
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Logo</label>
                                            <div class="row ckfinder-group">
                                                <div class="col-10">
                                                    <input type="text" class="form-control image-render" name="home_partner_content[logo][]"
                                                           placeholder="Đường dẫn logo đối tác..."
                                                           value="<?php echo $item['logo']; ?>">
                                                </div>
                                                <div class="col-2">
                                                    <button type="button" class="btn btn-success btn-block choose-image"><i class="fa fa-upload" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Link</label>
                                            <input type="text" class="form-control" name="home_partner_content[link][]" placeholder="Link đối tác..."
                                            value="<?php echo $item['link']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1">
                                <a href="" class="btn btn-danger btn-sm btn-block remove">&times;</a>
                            </div>
                        </div>
                    </div><!--End . partner-item-->
                    <?php endforeach; endif; ?>
                </div><!--End .partner-wrapper-->
                <p><button type="button" class="btn btn-warning btn-sm add-partner">Thêm đối tác</button></p>

                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
