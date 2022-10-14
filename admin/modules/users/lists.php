<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này hiện thị danh sách người dùng, phân trang, tìm kiếm, lọc
 * */

$data = [
    'pageTitle' => 'Quản lý người dùng'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$userId = isLogin()['user_id']; // Lấy userId đang đăng nhập

// Xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody();

    // Xử lý lọc status
    if (!empty($body['status'])) {
        $status = $body['status'];

        if ($status==2) {
            $statusSql = 0;
        } else {
            $statusSql = $status;
        }

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }

        $filter.= "$operator status=$statusSql";
    }

    // Xử lý lọc dữ liệu theo từ khóa
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator fullname LIKE '%$keyword%'";
    }

    // Xử lý lọc theo nhóm
    if (!empty($body['group_id'])) {
        $groupId = $body['group_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator group_id=$groupId";
    }
}

// Xử lý phân trang
$allUserNum = getRows("SELECT id FROM users $filter");

// 1. Xác định được số lượng bản ghi trên 1 trang
$perPage = _PER_PAGE; // Mỗi trang có 5 bản ghi

// 2. Tính tổng số trang
$maxPage = ceil($allUserNum/$perPage);

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
 * $page = 2 => offset = 3 = ($page-1)*$perPage
 * $page = 3 => offset = 6 = ($page-1)*$perPage
 * */
$offset = ($page-1)*$perPage;

// Truy vấn lấy tất cả bản ghi từ database có phân trang
$listAllUser = getRaw("SELECT users.*, groups.name as group_name FROM users INNER JOIN groups ON users.group_id=groups.id $filter 
                ORDER BY users.create_at DESC LIMIT $offset, $perPage");

// Truy vấn lấy danh sách nhóm
$alLGroups = getRaw("SELECT id, name FROM groups ORDER BY name");

// Xử lý query string tìm kiếm với phân trang
$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=users', '', $queryString);
    $queryString = str_replace('&page='.$page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&'.$queryString;
}

//
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <p>
                <a href="<?php echo getLinkAdmin('users', 'add'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Thêm người
                    dùng</a>
            </p>
            <hr>
            <form action="" method="get">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="0">Chọn trạng thái</option>
                                <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected' : false; ?>>
                                    Kích hoạt
                                </option>
                                <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected' : false; ?>>
                                    Chưa kích hoạt
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <select name="group_id" class="form-control">
                                <option value="0">Chọn nhóm</option>
                                <?php
                                if (!empty($alLGroups)) {
                                    foreach ($alLGroups as $item) {
                                        ?>
                                        <option value="<?php echo $item['id']; ?>"
                                        <?php echo (!empty($groupId) && $groupId=$item['id'])?'selected':false;
                                        ?>><?php echo $item['name']; ?></option>
                                        <?php 
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="search" name="keyword" class="form-control" placeholder="Từ khóa tìm kiếm..."
                               value="<?php echo (!empty($keyword)) ? $keyword : false ?>">
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                    </div>
                </div>
                <input type="hidden" name="module" value="users">
            </form>
            <?php
            getMsg($msg, $msg_type);
            ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Nhóm</th>
                    <th>Thời gian</th>
                    <th width="15%">Trạng thái</th>
                    <th width="10%">Sửa</th>
                    <th width="10%">Xóa</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($listAllUser)):
                    $count = 0; // Hiện thị số thứ tự
                    foreach ($listAllUser as $item):
                        $count++;
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><a href="<?php echo getLinkAdmin('users', 'edit', ['id'=>$item['id']]); ?>"><?php echo $item['fullname']; ?></a></td>
                            <td><?php echo $item['email']; ?></td>
                            <td><?php echo $item['group_name']; ?></td>
                            <td><?php echo (!empty($item['create_at']))?getDateFormat($item['create_at'], 'd/m/Y H:i:s'):false; ?></td>
                            <td class="text-center"><?php echo $item['status'] == 1 ? '<button type="button" class="btn btn-success btn-sm">
                                    Kích hoạt</button>' : '<button type="button" class="btn btn-warning btn-sm">
                                    Chưa kích hoạt</button>'; ?></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('users', 'edit', ['id'=>$item['id']]); ?>"
                                   class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>  Sửa</a></td>
                            <td class="text-center">
                                <?php if ($item['id']!=$userId): ?>
                                <a href="<?php echo getLinkAdmin('users', 'delete', ['id'=>$item['id']]); ?>"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa nhóm này?')"
                                   class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>  Xóa</a></td>
                                <?php endif; ?>
                        </tr>
                    <?php endforeach; else: ?>
                <tr>
                    <td colspan="7">
                        <div class="alert alert-danger class=" text-center
                        "">Không có người dùng
        </div>
        </td>
        </tr>
        <?php endif; ?>
        </tbody>
        </table>
        <nav aria-label="Page navigation example" style="display: flex; justify-content: right">
            <ul class="pagination">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<li class="page-item">
                <a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=users' . $queryString . '&page=' . $prevPage . '" 
                    aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
                </li>';
                }
                ?>
                <?php
                $begin = $page - 2;
                if ($begin < 1) {
                    $begin = 1;
                }
                $end = $page + 2;
                if ($end > $maxPage) {
                    $end = $maxPage;
                }
                for ($index = $begin; $index <= $end; $index++) { ?>
                    <li class="page-item <?php echo ($index == $page) ? 'active' : false ?>">
                        <a class="page-link"
                           href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=users' . $queryString . '&page=' . $index; ?>">
                            <?php echo $index; ?>
                        </a>
                    </li>
                <?php } ?>
                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item">
                <a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=users' . $queryString . '&page=' . $nextPage . '" 
                    aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
                </li>';
                }
                ?>
            </ul>
        </nav>
        <hr/>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<?php
layout('footer', 'admin', $data);
