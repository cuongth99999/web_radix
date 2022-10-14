<?php
if (!defined('_INCODE')) die('Access Denied...');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layout($layoutName='header', $dir='', $data = []) {
    if (!empty($dir)) {
        $dir = '/'.$dir;
    }
    if (file_exists(_WEB_PATH_TEMPLATES.$dir.'/layouts/'.$layoutName.'.php')) {
        require_once _WEB_PATH_TEMPLATES.$dir.'/layouts/'.$layoutName.'.php';
    }
}

function sendMail($to, $subject, $content) {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'cuongdz2003x@gmail.com';                     //SMTP username
        $mail->Password   = 'qcueonxkziaogqiq';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have
        // set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('cuongdz2003x@gmail.com', 'THC study PHP');
        $mail->addAddress($to);     //Add a recipient
//        $mail->addAddress('ellen@example.com');               //Name is optional
//        $mail->addReplyTo($to);
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');

        //Attachments
//        $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $mail->CharSet = 'UTF-8'; // Hiện tiêu đề tiếng việt
//        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        return $mail->send();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Kiểm tra phương thức POST
function isPost() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }

    return  false;
}

// Kiểm tra phương thức GET
function isGet() {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }

    return  false;
}

// Lấy giá trị phương thức POST, GET
function getBody($method='') {
    $bodyArr = [];

    if (empty($method)) {
        if (isGet()) {
            // Xử lý chuỗi trước khi hiện thị ra
            // return $_GET;
            /*
             * Đọc key của mảng $_GET
             * */
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
//                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS,
//                            FILTER_REQUIRE_ARRAY);
                        $bodyArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
//                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                        $bodyArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }

        if (isPost()) {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS,
                            FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    } else {
        if ($method=='get') {
            foreach ($_GET as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
//                    $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS,
//                        FILTER_REQUIRE_ARRAY);
                    $bodyArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
//                    $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    $bodyArr[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        } elseif ($method=='post') {
            foreach ($_POST as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS,
                        FILTER_REQUIRE_ARRAY);
                } else {
                    $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    return $bodyArr;
}

// Kiểm tra email
function isEmail($email) {
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

// Kiểm tra số nguyên
function isNumberInt($number, $range=[]) {
    /*
     * $range = ['min_range'=>1, 'max_range=20'];
     * */
    if (!empty($range)) {
        $options = ['options'=>$range];
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT, $options);
    } else {
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    }
    return $checkNumber;
}

// Kiểm tra số thực
function isNumberFloat($number, $range=[]) {
    /*
     * $range = ['min_range'=>1, 'max_range=20'];
     * */
    if (!empty($range)) {
        $options = ['options'=>$range];
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT, $options);
    } else {
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    }
    return $checkNumber;
}

// Kiểm tra số điện thoại (Bắt đầu bằng số 0, nối tiếp là 9 số)
function isPhone($phone) {
    $checkFirstZero = false;
    if ($phone[0] == '0') {
        $checkFirstZero = true;
        $phone = substr($phone, 1);
    }

    $checkNumberLast = false;
    if (isNumberInt($phone) && strlen($phone) == 9) {
        $checkNumberLast = true;
    }

    if ($checkFirstZero && $checkNumberLast) {
        return true;
    }

    return  false;
}

// Hàm tạo hiện thị thông báo
function getMsg($msg, $type='succsess') {
    if (!empty($msg)) {
        echo '<div class="alert alert-'.$type.'">';
        echo $msg;
        echo '</div>';
    }
}

// Hàm chuyển hướng
function redirect($path='index.php', $fullUrl=false) {
    if (empty($fullUrl)) {
        $url = _WEB_HOST_ROOT.'/'.$path;
    } else {
        $url = $path;
    }
    header("Location: $url");
    exit();
}

// Hàm thông báo lỗi
function form_error($fieldName, $errors, $beforeHtml='', $afterHtml='') {
    return (!empty($errors[$fieldName]))?$beforeHtml.reset($errors[$fieldName]).$afterHtml:null;
}

// Hàm hiện dữ liệu cũ
function old($fieldName, $oldData, $defualt=null) {
    return (!empty($oldData[$fieldName]))?$oldData[$fieldName]:$defualt;
}

// Hàm kiểm tra trạng thái đăng nhập
function isLogin() {
    $checkLogin = false;
    if (getSession('loginToken')) {
        $tokenLogin = getSession('loginToken');
        $queryToken = firstRaw("SELECT user_id FROM logintoken WHERE token='$tokenLogin'");

        if(!empty($queryToken)) {
//            $checkLogin = true;
            $checkLogin = $queryToken;
        } else {
            removeSession('loginToken');
        }
    }
    return $checkLogin;
}

// Hàm tự động xóa loginToken nếu đăng xuất
function autoRemoveTokenLogin() {
    $allUsers = getRaw("SELECT * FROM users WHERE status=1");
    if (!empty($allUsers)) {
        foreach ($allUsers as $user) {
            $now = date('Y-m-d H:i:s');
            $before = (string)$user['last_activity'];
            $dift = strtotime($now) - strtotime($before);
            $dift = floor($dift/60);

            if ($dift >= 30) {
                delete('logintoken', "user_id=".$user['id']);
            }
        }
    }
}

// Lưu lại thời gian cuối cùng hoạt động
function saveActivity() {
    $user_id = isLogin()['user_id'];
    update('users', ['last_activity'=>date('Y-m-d H:i:s')], "id=$user_id");
}

// Lấy thông tin user
function getUserInfo($user_id) {
    $info = firstRaw("SELECT * FROM users WHERE id=$user_id");
    return $info;
}

// Active menu sidebar
function activeMenuSidebar($module) {
    $body = getBody('get');

    if (empty($body)) {
        $body['module'] = '';
    }
    if ($body['module']==$module) {
        return true;
    }
    return false;
}

// Get Link
function getLinkAdmin($module, $action='', $params = []) {
    $url = _WEB_HOST_ROOT_ADMIN;
    $url = $url.'?module='.$module;
    if (!empty($action)) {
        $url .= '&action='.$action;
    }

    /*
     * params = ['id'=>1, 'keyword'=>'THC']
     * =>paramsString = id=1&keyword=THC
     * */

    if (!empty($params)) {
        $paramsString = http_build_query($params);
        $url = $url.'&'.$paramsString;
    }
    return $url;
}

// Hàm Format Date
function getDateFormat($strDate, $format) {
    $dateObject = date_create($strDate);
    if (!empty($dateObject)) {
        return date_format($dateObject, $format);
    }
    return  false;
}

// Check font-awesome icon
function isFontIcon($input) {
    $input = html_entity_decode($input);
    if (strpos($input, '<i class="') !== false) {
        return true;
    }
    return false;
}

// Update QueryString
function getLinkQueryString($queryString, $key, $value) {
    $queryArr = explode('&', $queryString);
    $queryArr = array_filter($queryArr);

    $queryFinal = '';

    if (!empty($queryArr)) {
        foreach ($queryArr as $item) {
            $itemArr = explode('=', $item);
            if (!empty($itemArr)) {
                if ($itemArr[0] == $key) {
                    $itemArr[1] = $value;
                }
                $item = implode('=', $itemArr);

                $queryFinal .= $item.'&';
            }
        }
    }

    if (!empty($queryFinal)) {
        $queryFinal = rtrim($queryFinal, '&');
    } else {
        $queryFinal = $queryString;
    }
    return $queryFinal;
}

function setExceptionError($exception) {
    if (_DEBUG) {

        setFlashData('debug_error', [
            'error_code' => $exception->getCode(),
            'error_message' => $exception->getMessage(),
            'error_file' => $exception->getFile(),
            'error_line' => $exception->getLine()
        ]);

        $reload = getFlashData('reload');

        if (!$reload) {
            setFlashData('reload', 1);
            if (isAdmin()) {
                redirect(getPathAdmin());
            } else {
                redirect(getPath());
            }
        }
        die();
    } else {
//        removeSession('reload');
//        removeSession('debug_error');
        require_once _WEB_PATH_ROOT . '/modules/errors/500.php';
    }
}

function setErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!_DEBUG) {
        require_once _WEB_PATH_ROOT . '/modules/errors/500.php';
//        removeSession('reload');
//        removeSession('debug_error');
        return;
    }

    setFlashData('debug_error', [
        'error_code' => $errno,
        'error_message' => $errstr,
        'error_file' => $errfile,
        'error_line' => $errline,
    ]);

    $reload = getFlashData('reload');

    if (!$reload) {
        setFlashData('reload', 1);
        if (isAdmin()) {
            redirect(getPathAdmin());
        } else {
            redirect(getPath());
        }
    }
    die();

//    throw new ErrorException($errstr, $errno, 1, $errfile, $errline);
}

function loadExceptionError() {
    $debugError = getFlashData('debug_error');


    if (!empty($debugError)) {
        if (_DEBUG) {
            require_once _WEB_PATH_ROOT . '/modules/errors/exception.php';
        } else {
            require_once _WEB_PATH_ROOT . '/modules/errors/500.php';
        }
    }
}

function getPathAdmin() {
    $path = 'admin';
    if (!empty($_SERVER['QUERY_STRING'])) {
        $path .= '?'.trim($_SERVER['QUERY_STRING']);
    }

    return $path;
}

function getPath() {
    $path = '';
    if (!empty($_SERVER['QUERY_STRING'])) {
        $path .= '?'.trim($_SERVER['QUERY_STRING']);
    }

    return $path;
}

// Kiểm tra trang hiện tại có phải trang admin hay không?
function isAdmin() {
    if (!empty($_SERVER['PHP_SELF'])) {
        $currentFile = !empty($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:false;
        $dirFile = dirname($currentFile);
        $baseNameDir = basename($dirFile);
        if (trim($baseNameDir) == 'admin') {
            return true;
        }
    }
    return  false;
}

function getOption($key, $type='') {
    $sql = "SELECT * FROM options WHERE opt_key='$key'";
    $option = firstRaw($sql);
    if (!empty($option)) {
        if ($type == 'label') {
            return $option['name'];
        }

        return $option['opt_value'];
    }

    return  false;
}

function updateOptions($data=[]) {
    if (isPost()) {
        $allFields = getBody();

        if (!empty($data)) {
            $keysDataArr = array_keys($data);
            $valuesDataArr = array_values($data);

            foreach ($keysDataArr as $key => $value) {
                $allFields[$value] = $valuesDataArr[$key];
            }
        }
        $countUpdate = 0;
        if (!empty($allFields)) {
            foreach ($allFields as $field => $value) {
                $condition = "opt_key = '$field'";
                $dataUpdate = [
                    'opt_value' => trim($value)
                ];
                $updateStatus = update('options', $dataUpdate, $condition);
                if ($updateStatus) {
                    $countUpdate++;
                }
            }
        }

        if ($countUpdate > 0) {
            setFlashData('msg', 'Đã cập nhật '.$countUpdate.' bản ghi thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Cập nhật không thành công!');
            setFlashData('msg_type', 'error');
        }

        redirect(getPathAdmin()); // Reload trang
    }
}

function getCountContact() {
    $sql = "SELECT id FROM contacts WHERE status=0";
    $count = getRows($sql);
    return $count;
}

function head() {
    ?>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ROOT; ?>/templates/core/css/style.css?ver=<?php echo rand(); ?>">
    <?php
}

function foot() {

}

function loadError($name='404') {
    $pathError = _WEB_PATH_ROOT.'/modules/errors/'.$name.'.php';
    require_once $pathError;
    die();
}

function getYoutubeId($url) {
    $result = [];
    $urlStr = parse_url($url, PHP_URL_QUERY);
    parse_str($urlStr, $result);

    if (!empty($result['v'])) {
        return $result['v'];
    }

    return  false;
}

// Hàm cắt chữ
function getLimitText($content, $limit=20) {
    $content = strip_tags($content);
    $content = trim($content);
    $contentArr = explode(' ', $content);
    $contentArr = array_filter($contentArr);
    $wordsNumber = count($contentArr);
    if ($wordsNumber > $limit) {
        $contentArrLimit = explode(' ', $content, $limit+1);
        array_pop($contentArrLimit);

        $limitText = implode(' ', $contentArrLimit).'...';

        return $limitText;
    }

    return $content;
}

// Hàm tăng lượt view
function setView($id) {
    $blog = firstRaw('SELECT view_count FROM blog WHERE id='.$id);

    $check = false;

    if (!empty($blog)) {
        $view = $blog['view_count'];
        $view++;
        $check = true;
    } else {
        if (is_array($blog)) {
            $view = 1;
            $check = true;
        }
    }

    if ($check) {
        update('blog', [
            'view_count' => $view
        ], "id=$id");
    }
}

// Lấy avatar từ GrAvatar
function getAvatar($email, $size=null) {
    $hashGravatar = md5($email);
    if (!empty($size)) {
        $avatarUrl = 'https://www.gravatar.com/avatar/'.$hashGravatar.'?s='.$size;
    } else {
        $avatarUrl = 'https://www.gravatar.com/avatar/'.$hashGravatar;
    }

    return $avatarUrl;
}

function getCommentList($commentData, $parentId, $id) {
    if (!empty($commentData)) {
        echo '<div class="comment-children">';
        foreach ($commentData as $key => $item) {
            if ($item['parent_id']==$parentId) {
                ?>
                <div class="comment-list">
                    <div class="head">
                        <img src="<?php echo getAvatar($item['email']) ?>" alt="#">
                    </div>
                    <div class="body">
                        <h4><?php echo $item['name']; echo !empty($item['user_id'])?'<span class="badge badge-success" style="margin-left: 8px">'.$item['group_name'].'</span>':false; ?></h4>
                        <div class="comment-info">
                            <p><span><?php echo getDateFormat($item['create_at'], 'd/m/y'); ?> vào<i class="fa fa-clock-o"></i><?php echo getDateFormat($item['create_at'], 'H:i'); ?>,</span><a
                                        href="<?php echo _WEB_HOST_ROOT.'?module=blog&action=detail&id='.$id.'&comment_id='.$item['id']; ?>#comment-form"><i class="fa fa-comment-o"></i>Trả lời</a></p>
                        </div>
                        <p><?php echo $item['content']; ?></p>
                    </div>
                </div>
                <?php
                getCommentList($commentData, $item['id'], $id);
                unset($commentData[$key]);
            }
        }
        echo '</div>';
    }
}

function getComment($commentId) {
    $commentData = firstRaw("SELECT * FROM comments WHERE id=$commentId");
    return $commentData;
}

// Đệ quy lấy tất cả trả lời của 1 bình luận => gán vào mảng
function getCommentReply($commentData, $parentId, &$result=[]) {
    if (!empty($commentData)) {
        foreach ($commentData as $key => $item) {
            if ($parentId==$item['parent_id']) {
                $result[] = $item['id'];
                getCommentReply($commentData, $item['id'], $result);
                unset($commentData[$key]);
            }
        }
    }
    return $result;
}

// Lấy số lượng comment theo trạng thái
function getCommentCount($status=0) {
    $sql = "SELECT id FROM comments WHERE status=$status";
    return getRows($sql);
}

// Lấy thông tin của phòng ban
function getContactType($typeId) {
    $sql = "SELECT * FROM contact_type WHERE id=$typeId";
    return firstRaw($sql);
}

// Lấy số lượng đăng ký nhận tin chưa duyệt
function getSubsribe($status=0) {
    $sql = "SELECT id FROM subscribe WHERE status=$status";
    return getRows($sql);
}

// Đổ dữ liệu Menu
function getMenu($dataMenu, $isSub=false) {
    if (!empty($dataMenu)) {
        echo ($isSub)?'<ul class="dropdown">':'<ul class="nav menu">';

        foreach ($dataMenu as $key => $item) {

            if (empty($item['children'])) {
                echo '<li class=""><a href="'.$item['href'].'" target="'.$item['target'].'" title="'.$item['title'].'">'.$item['text'].'</a>';
            } else {
                echo '<li class=""><a href="'.$item['href'].'" target="'.$item['target'].'" title="'.$item['title'].'">'.$item['text'].' <i class="fa fa-caret-down"></i></a>';
            }

            // Gọi đệ quy
            if (!empty($item['children'])) {
                getMenu($item['children'], true);
            }

            echo '</li>';
        }
        echo '</ul>';
    }
}