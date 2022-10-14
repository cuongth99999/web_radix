<?php
if (!defined('_INCODE')) die('Access Denied...');
/*
 * File này hiện thị danh sách dự án, phân trang, tìm kiếm, lọc
 * */

$data = [
    'pageTitle' => 'Quản lý dự án'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody();

    // Xử lý lọc theo người đăng
    if (!empty($body['user_id'])) {
        $userId = $body['user_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator portfolios.user_id=$userId";
    }

    // Xử lý lọc dữ liệu theo từ khóa
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator portfolios.name LIKE '%$keyword%'";
    }

    // Xử lý lọc theo danh mục
    if (!empty($body['cate_id'])) {
        $cateId = $body['cate_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator portfolio_category_id=$cateId";
    }
}

// Xử lý phân trang
$allPortfoliosNum = getRows("SELECT id FROM portfolios $filter");

// 1. Xác định được số lượng bản ghi trên 1 trang
$perPage = _PER_PAGE; // Mỗi trang có 5 bản ghi

// 2. Tính tổng số trang
$maxPage = ceil($allPortfoliosNum / $perPage);

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
$offset = ($page - 1) * $perPage;

// Truy vấn lấy tất cả bản ghi từ database có phân trang
$listAllPortfolios = getRaw("SELECT portfolios.*, portfolio_categories.name as cate_name, portfolio_categories.id as cate_id, users.fullname FROM portfolios INNER JOIN portfolio_categories ON portfolios.portfolio_category_id=portfolio_categories.id 
                INNER JOIN users ON portfolios.user_id = users.id $filter 
                ORDER BY portfolios.create_at DESC LIMIT $offset, $perPage");

// Truy vấn lấy danh sách danh mục
$allPortfolio_categories = getRaw("SELECT id, name FROM portfolio_categories ORDER BY name");

// Lấy dữ liệu tất cả người dùng
$allUsers = getRaw("SELECT * FROM users ORDER BY fullname");

// Xử lý query string tìm kiếm với phân trang
$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=portfolios', '', $queryString);
    $queryString = str_replace('&page=' . $page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&' . $queryString;
}

//
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <p>
                <a href="<?php echo getLinkAdmin('portfolios', 'add'); ?>" class="btn btn-success"><i
                            class="fa fa-plus"></i> Thêm dự án</a>
            </p>
            <hr>
            <form action="" method="get">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <select class="form-control" name="user_id">
                                <option value="0">Chọn người đăng</option>
                                <?php
                                if (!empty($allUsers)) {
                                    foreach ($allUsers as $item) {
                                        ?>
                                        <option value="<?php echo $item['id']; ?>" <?php echo (!empty($userId) && $userId == $item['id']) ? 'selected' : false ?>
                                        ><?php echo $item['fullname']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <select name="cate_id" class="form-control">
                                <option value="0">Chọn danh mục</option>
                                <?php
                                if (!empty($allPortfolio_categories)) {
                                    foreach ($allPortfolio_categories as $item) {
                                        ?>
                                        <option value="<?php echo $item['id']; ?>"
                                            <?php echo (!empty($cateId) && $cateId = $item['id']) ? 'selected' : false;
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
                <input type="hidden" name="module" value="portfolios">
            </form>
            <?php
            getMsg($msg, $msg_type);
            ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th>Tên</th>
                    <th width="20%">Danh mục</th>
                    <th width="15%">Người đăng</th>
                    <th width="15%">Thời gian</th>
                    <th width="10%">Sửa</th>
                    <th width="10%">Xóa</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($listAllPortfolios)):
                    $count = 0; // Hiện thị số thứ tự
                    foreach ($listAllPortfolios as $item):
                        $count++;
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td>
                                <a href="<?php echo getLinkAdmin('portfolios', 'edit', ['id' => $item['id']]); ?>"><?php echo $item['name']; ?></a>
                                <a href="<?php echo getLinkAdmin('portfolios', 'duplicate', ['id'=>$item['id']]); ?>" class="btn-success btn-sm" style="float: right">Nhân bản</a>
                            </td>
                            <td><a href="#"><?php echo $item['cate_name']; ?></a></td>
                            <td><a href="#"><?php echo $item['fullname']; ?></a></td>
                            <td><?php echo (!empty($item['create_at'])) ? getDateFormat($item['create_at'], 'd/m/Y H:i:s') : false; ?></td>
                            <td class="text-center"><a
                                        href="<?php echo getLinkAdmin('portfolios', 'edit', ['id' => $item['id']]); ?>"
                                        class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Sửa</a></td>
                            <td class="text-center">
<!--                                --><?php //if ($item['id'] != $portfolioId): ?>
                                <a href="<?php echo getLinkAdmin('portfolios', 'delete', ['id' => $item['id']]); ?>"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa dự án này?')"
                                   class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Xóa</a></td>
<!--                            --><?php //endif; ?>
                        </tr>
                    <?php endforeach; else: ?>
                <tr>
                    <td colspan="7">
                        <div class="alert alert-danger" class="text-center">Không có dự án</div>
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
                <a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=portfolios' . $queryString . '&page=' . $prevPage . '" 
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
                           href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=portfolios' . $queryString . '&page=' . $index; ?>">
                            <?php echo $index; ?>
                        </a>
                    </li>
                <?php } ?>
                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item">
                <a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=portfolios' . $queryString . '&page=' . $nextPage . '" 
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
