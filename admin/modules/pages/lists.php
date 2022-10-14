<?php
if (!defined('_INCODE')) die('Access Denied...');
$data = [
    'pageTitle' => 'Danh sách trang'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody();

    // Xử lý lọc dữ liệu theo từ khóa
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator title LIKE '%$keyword%'";
    }

    // Xử lý lọc theo người đăng
    if (!empty($body['user_id'])) {
        $userId = $body['user_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator user_id=$userId";
    }
}


// Xử lý phân trang
// Lấy số lượng bản ghi
$allPagesNum = getRows("SELECT id FROM pages $filter");

// 1. Xác định được số lượng bản ghi trên 1 trang
$perPage = _PER_PAGE; // Mỗi trang có 5 bản ghi

// 2. Tính tổng số trang
$maxPage = ceil($allPagesNum/$perPage);

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
    $queryString = str_replace('module=pages', '', $queryString);
    $queryString = str_replace('&page='.$page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&'.$queryString;
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

// Lấy dữ liệu dịch vụ
$listPages = getRaw("SELECT pages.*, users.fullname as fullname, users.id as user_id FROM pages INNER JOIN users ON pages.user_id=users.id 
$filter ORDER BY pages.create_at DESC LIMIT $offset, $perPage");

// Lấy dữ liệu tất cả người dùng
$allUsers = getRaw("SELECT id, fullname, email FROM users ORDER BY fullname");

?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <a href="<?php echo getLinkAdmin('pages', 'add'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Thêm trang</a>
            <hr>
            <form action="" method="get" style="padding-bottom: 15px">
                <div class="row">
                    <div class="col-3">
                        <select class="form-control" name="user_id">
                            <option value="0">Chọn người đăng</option>
                            <?php
                            if (!empty($allUsers)) {
                                foreach ($allUsers as $item) {
                                    ?>
                                    <option value="<?php echo $item['id']; ?>" <?php echo (!empty($userId) && $userId == $item['id'])?'selected':false ?>
                                    ><?php echo $item['fullname']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <input type="search" name="keyword" class="form-control" placeholder="Nhập tên trang ..."
                               value="<?php echo (!empty($keyword))?$keyword:false ?>">
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                    </div>
                </div>
                <input type="hidden" name="module" value="pages">
            </form>
            <?php
            getMsg($msg, $msg_type);
            ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th>Tiêu đề</th>
                    <th width="15%">Người đăng</th>
                    <th width="10%">Thời gian</th>
                    <th width="10%">Xem</th>
                    <th width="10%">Sửa</th>
                    <th width="10%">Xoá</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($listPages)):
                    $count = 0; // Hiện thị số thứ tự
                    foreach ($listPages as $item):
                        $count++;
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><a href="<?php echo getLinkAdmin('pages', 'edit', ['id'=>$item['id']]); ?>">
                                    <?php echo $item['title']; ?></a><a href="<?php echo getLinkAdmin('pages', 'duplicate', ['id'=>$item['id']]); ?>" class="btn-success btn-sm" style="float: right">Nhân bản</a>
                            </td>
                            <td><a href="?<?php echo getLinkQueryString($queryString, 'user_id', $item['user_id']);?>"><?php echo $item['fullname']; ?></td>
                            <td><?php echo getDateFormat($item['create_at'], 'd/m/Y H:i:s'); ?></td>
                            <td class="text-center">
                                <a href="<?php echo getLinkModule('pages', $item['id']); ?>" class="btn btn-primary btn-sm" target="_blank">
                                    Xem
                                </a>
                            </td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('pages', 'edit', ['id'=>$item['id']]); ?>"
                                                       class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>  Sửa</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('pages', 'delete', ['id'=>$item['id']]); ?>"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa trang này?')"
                                                       class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>  Xóa</a></td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="8" class="text-center">Không có trang</td>
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
                <a class="page-link" href="'._WEB_HOST_ROOT_ADMIN.'?module=pages'.$queryString.'&page='.$prevPage.'" 
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
                            <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN.'?module=pages'.$queryString.'&page='.$index; ?>">
                                <?php echo $index; ?>
                            </a>
                        </li>
                    <?php }?>
                    <?php
                    if ($page < $maxPage) {
                        $nextPage = $page+1;
                        echo '<li class="page-item">
                <a class="page-link" href="'._WEB_HOST_ROOT_ADMIN.'?module=pages'.$queryString.'&page='.$nextPage.'" 
                    aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
                </li>';
                    }
                    ?>
                </ul>
            </nav>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

<?php
layout('footer', 'admin', $data);

