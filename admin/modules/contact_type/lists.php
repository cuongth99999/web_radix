<?php
if (!defined('_INCODE')) die('Access Denied...');
$data = [
    'pageTitle' => 'Danh sách phòng ban'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Xử lý lọc dữ liệu
$filter = '';

$view = 'add'; //Mặc định

$id = 0; // Id mặc định

$body = getBody('get');

// Xử lý lọc dữ liệu theo từ khóa
if (!empty($body['keyword'])) {
    $keyword = $body['keyword'];

    if (!empty($keyword)) {
        $filter .= "WHERE name LIKE '%$keyword%'";
    }
}

if (!empty($body['view'])) {
    $view = $body['view'];
}

if (!empty($body['id'])) {
    $id = $body['id'];
}

// Xử lý phân trang
// Lấy số lượng bản ghi
$allContactTypeNum = getRows("SELECT id FROM contact_type $filter");

// 1. Xác định được số lượng bản ghi trên 1 trang
$perPage = _PER_PAGE; // Mỗi trang có 5 bản ghi

// 2. Tính tổng số trang
$maxPage = ceil($allContactTypeNum/$perPage);

// 3. Xử lý số trang dựa vào phương thức GET
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}

// 4. Tính toán offset trong LIMIT dựa vào biến $page
/*
 * $page = 1 => offset = 0 = ($page-1)*$perPage
 * $page = 2 => offset = 5 = ($page-1)*$perPage
 * $page = 3 => offset = 10 = ($page-1)*$perPage
 * */
$offset = ($page-1)*$perPage;

// Xử lý query string tìm kiếm với phân trang
$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=contact_type', '', $queryString);
    $queryString = str_replace('&page='.$page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&'.$queryString;
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

// Lấy dữ liệu danh sách phòng ban
$listContactType = getRaw("SELECT *, (SELECT count(contacts.id) FROM contacts
WHERE contacts.type_id = contact_type.id) as contact_count
FROM contact_type $filter ORDER BY create_at DESC LIMIT $offset, $perPage");

?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php
            getMsg($msg, $msg_type);
            ?>
            <div class="row">
                <div class="col-6">
                    <h4>Danh sách phòng ban</h4>
                    <form action="" method="get" style="padding-bottom: 15px">
                        <div class="row">
                            <div class="col-9">
                                <input type="search" name="keyword" class="form-control" placeholder="Nhập tên phòng ban..."
                                       value="<?php echo (!empty($keyword))?$keyword:false ?>">
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                            </div>
                        </div>
                        <input type="hidden" name="module" value="contact_type">
                    </form>
                    <hr>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="5%">STT</th>
                            <th>Tên</th>
                            <th width="20%">Thời gian</th>
                            <th width="10%">Sửa</th>
                            <th width="10%">Xoá</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($listContactType)):
                            $count = 0; // Hiện thị số thứ tự
                            foreach ($listContactType as $item):
                                $count++;
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><a href="<?php echo getLinkAdmin('contact_type', '', ['id'=>$item['id'], 'view'=>'edit']); ?>">
                                            <?php echo $item['name']; ?></a> (<?php echo $item['contact_count']; ?>)
                                        <a href="<?php echo getLinkAdmin('contact_type', 'duplicate', ['id'=>$item['id']]); ?>" class="btn-success btn-sm" style="float: right">Nhân bản</a></td>
                                    <td><?php echo getDateFormat($item['create_at'], 'd/m/Y H:i:s'); ?></td>
                                    <td class="text-center"><a href="<?php echo getLinkAdmin('contact_type', '' ,['id'=>$item['id'], 'view'=>'edit']); ?>"
                                                               class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a></td>
                                    <td class="text-center"><a href="<?php echo getLinkAdmin('contact_type', '', ['id'=>$item['id'], 'view'=>'delete']); ?>"
                                                               onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')"
                                                               class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></td>
                                </tr>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td colspan="5" class="text-center">Không có danh sách phòng ban</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation example" style="display: flex; justify-content: right">
                        <ul class="pagination">
                            <?php
                            if ($page > 1) {
                                $prevPage = $page-1;
                                echo '<li class="page-item">
                <a class="page-link" href="'._WEB_HOST_ROOT_ADMIN.'?module=contact_type'.$queryString.'&page='.$prevPage.'" 
                    aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
                </li>';
                            }
                            ?>
                            <?php
                            $begin = $page-2;
                            if ($begin < 1) {
                                $begin = 1;
                            }
                            $end = $page+2;
                            if ($end > $maxPage) {
                                $end = $maxPage;
                            }
                            for ($index = $begin; $index <= $end; $index++) { ?>
                                <li class="page-item <?php echo ($index==$page)?'active':false ?>">
                                    <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN.'?module=contact_type'.$queryString.'&page='.$index; ?>">
                                        <?php echo $index; ?>
                                    </a>
                                </li>
                            <?php }?>
                            <?php
                            if ($page < $maxPage) {
                                $nextPage = $page+1;
                                echo '<li class="page-item">
                <a class="page-link" href="'._WEB_HOST_ROOT_ADMIN.'?module=contact_type'.$queryString.'&page='.$nextPage.'" 
                    aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
                </li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>

                <div class="col-6">
                    <?php
                    if (!empty($view) && !empty($id)) {
                        require_once $view.'.php';
                    } else {
                        require_once 'add.php';
                    }
                    ?>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

<?php
layout('footer', 'admin', $data);

