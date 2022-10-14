<?php
if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => getOption('contact_title')
];
layout('header', 'client', $data);
layout('breadcrumb', 'client', $data);

$title = getOption('contact_primary_title');
$titleBg = getOption('contact_title_bg');
$desc = getOption('contact_title_desc');

$facebook = getOption('general_facebook');
$twitter = getOption('general_twitter');
$linkedin = getOption('general_linkedin');
$youtube = getOption('general_youtube');
$behance = getOption('general_behance');

// Truy vấn lấy phòng ban
$contactTypeLists = getRaw("SELECT * FROM contact_type ORDER BY name ASC");

// Xử lý gửi liên hệ
if (isPost()) {
    $body = getBody(); // Lấy tất cả dữ liệu của form

    $errors = [];

    // Validate fullname
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Tên không được để trống';
    } else {
        if (strlen(trim($body['fullname']))<5) {
            $errors['fullname']['min'] = 'Tên phải lớn hơn hoặc bằng 5 ký tự';
        }
    }

    // Validate email: Bắt buộc phải nhập, Định dạng email, Email phải duy nhất
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        if (!isEmail(trim($body['email']))) {
            $errors['email']['isEmail'] = 'Email không hợp lệ';
        }
    }

    // Validate content
    if (empty($body['message'])) {
        $errors['message']['required'] = 'Nội dung liên hệ không được để trống';
    } else {
        if (strlen(trim($body['message']))<5) {
            $errors['message']['min'] = 'Nội dung liên hệ phải lớn hơn hoặc bằng 5 ký tự';
        }
    }

    if (empty($errors)) {
        // Xử lý gửi liên hệ vào database
        $dataInsert = [
                'fullname' => trim(strip_tags($body['fullname'])),
                'email' => trim(strip_tags($body['email'])),
                'type_id' => trim(strip_tags($body['type_id'])),
                'message' => trim(strip_tags($body['message'])),
                'status' => 0,
                'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('contacts', $dataInsert);

        if ($insertStatus) {
            setFlashData('msg', 'Liên hệ đã được gửi đi thành công. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất!');
            setFlashData('msg_type', 'success');

            $contactType = getContactType($dataInsert['type_id']);

            $siteName = getOption('general_sitename');

            // Gửi email cho khách hàng
            $subjectCustomer = '['.$siteName.'] Cảm ơn bạn đã gửi liên hệ';
            $contentCustomer = '<p>Chào <b>'.$dataInsert['fullname'].'</b></p>';
            $contentCustomer .= '<p>Cảm ơn bạn đã gửi liên hệ cho chúng tôi. Dưới đây là thông tin của bạn</p>';
            $contentCustomer .= '
                <p>Họ và tên: '.$dataInsert['fullname'].'</p>
                <p>Email: '.$dataInsert['email'].'</p>
                <p>Nội dung: '.$dataInsert['message'].'</p>
                <p>Thời gian gửi: '.$dataInsert['create_at'].'</p>
                <p>Phòng ban: '.$contactType['name'].'</p>
                <p>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất</p>
                <p>Trân trọng!</p>
            ';

            sendMail($dataInsert['email'], $subjectCustomer, $contentCustomer);

            // Gửi email cho admin
            $subjectAdmin = '['.$siteName.'] '.$dataInsert['fullname'].' đã gửi liên hệ';
            $contentAdmin = '
                <p>Họ và tên: '.$dataInsert['fullname'].'</p>
                <p>Email: '.$dataInsert['email'].'</p>
                <p>Nội dung: '.$dataInsert['message'].'</p>
                <p>Thời gian gửi: '.$dataInsert['create_at'].'</p>
                <p>Phòng ban: '.$contactType['name'].'</p>
                <p>Thông tin được gửi từ : '._WEB_HOST_ROOT.'</p>
            ';

            sendMail(getOption('general_email'), $subjectAdmin, $contentAdmin);

        } else {
            setFlashData('msg', 'Bạn không thể gửi liên hệ vào lúc này. Vui lòng thử lại sau!');
            setFlashData('msg_type', 'danger');
        }
    } else {
        // Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('erros', $errors);
        setFlashData('old', $body);
    }
    redirect('lien-he.html');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('erros');
?>
<!-- Start Contact -->
<section id="contact-us" class="contact-us section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
                    if (!empty($titleBg)) {
                        echo '<span class="title-bg">'.$titleBg.'</span>';
                    }
                    echo !empty($title)?'<h1>'.$title.'</h1>':false;
                    echo !empty($desc)?'<p>'.$desc.'</p>':false;
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="contact-main">
                    <div class="row">
                        <!-- Contact Form -->
                        <div class="col-lg-8 col-12">
                            <div class="form-main">
                                <div class="text-content">
                                    <h2>Gửi liên hệ cho chúng tôi</h2>
                                </div>
                                <?php
                                getMsg($msg, $msg_type);
                                ?>
                                <form class="form" method="post">
                                    <div class="row">
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <input type="text" name="fullname" placeholder="Họ và tên...">
                                                <?php echo form_error('fullname', $errors,
                                                    '<span class="error">', '</span>') ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <input type="email" name="email" placeholder="Email...">
                                                <?php echo form_error('email', $errors,
                                                    '<span class="error">', '</span>') ?>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <select name="type_id">
                                                    <?php
                                                    if (!empty($contactTypeLists)){
                                                        foreach ($contactTypeLists as $item) {
                                                            echo '<option value="'.$item['id'].'" class="option">'.$item['name'].'</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-12">
                                            <div class="form-group">
                                                <textarea name="message" rows="6" placeholder="Nội dung..." ></textarea>
                                                <?php echo form_error('message', $errors,
                                                    '<span class="error">', '</span>') ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-12">
                                            <div class="form-group button">
                                                <button type="submit" class="btn primary">Gửi liên hệ</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--/ End Contact Form -->
                        <!-- Contact Address -->
                        <div class="col-lg-4 col-12">
                            <div class="contact-address">
                                <!-- Address -->
                                <div class="contact">
                                    <h2>Our Contact Address</h2>
                                    <ul class="address">
                                        <li><i class="fa fa-paper-plane"></i><span>Address: </span> <?php echo getOption('general_adress'); ?></li>
                                        <li><i class="fa fa-phone"></i><span>Phone: </span><?php echo getOption('general_hotline'); ?></li>
                                        <li class="email"><i class="fa fa-envelope"></i><span>Email: </span><a href="mailto:<?php echo getOption('general_email'); ?>"><?php echo getOption('general_email'); ?></a></li>
                                    </ul>
                                </div>
                                <!--/ End Address -->
                                <!-- Social -->
                                <ul class="social">
                                    <?php
                                    if (!empty($facebook)) {
                                        echo '<li class="active"><a href="'.$facebook.'" target="_blank"><i class="fa fa-facebook"></i>Like Us facebook</a></li>';
                                    }

                                    if (!empty($twitter)) {
                                        echo '<li><a href="'.$twitter.'" target="_blank"><i class="fa fa-twitter"></i>Follow Us Twitter</a></li>';
                                    }

                                    if (!empty($linkedin)) {
                                        echo '<li><a href="'.$linkedin.'" target="_blank"><i class="fa fa-linkedin"></i>Follow Us linkedin</a></li>';
                                    }

                                    if (!empty($youtube)) {
                                        echo '<li><a href="'.$youtube.'" target="_blank"><i class="fa fa-youtube"></i>Follow Us Youtube</a></li>';
                                    }
                                    if (!empty($behance)) {
                                        echo '<li><a href="'.$behance.'" target="_blank"><i class="fa fa-behance"></i>Follow Us behance</a></li>';
                                    }

                                    ?>
                                </ul>
                                <!--/ End Social -->
                            </div>
                        </div>
                        <!--/ End Contact Address -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ End Contact -->
<?php
require_once _WEB_PATH_ROOT.'/modules/home/contents/partner.php';
layout('footer', 'client');
?>
