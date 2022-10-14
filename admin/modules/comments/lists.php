<?php
if (!defined('_INCODE')) die('Access Denied...');
$data = [
    'pageTitle' => 'Danh sách bình luận'
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
        $filter .= " $operator comments.name LIKE '%$keyword%' OR comments.email LIKE '%$keyword%' OR comments.website LIKE '%$keyword%' 
        OR comments.content LIKE '%$keyword%'";
    }

    // Xử lý lọc theo người dùng
    if (!empty($body['user_id'])) {
        $userId = $body['user_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator comments.user_id=$userId";
    }

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

        $filter.= " $operator comments.status=$statusSql";
    }
}


// Xử lý phân trang
// Lấy số lượng bản ghi
$allCommentsNum = getRows("SELECT id FROM comments $filter");

// 1. Xác định được số lượng bản ghi trên 1 trang
$perPage = _PER_PAGE; // Mỗi trang có 5 bản ghi

// 2. Tính tổng số trang
$maxPage = ceil($allCommentsNum/$perPage);

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
    $queryString = str_replace('module=comments', '', $queryString);
    $queryString = str_replace('&page='.$page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&'.$queryString;
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

// Lấy dữ liệu bình luận
$listComments = getRaw("SELECT comments.*, blog.title, users.fullname, users.email as user_email FROM comments INNER JOIN blog ON comments.blog_id=blog.id LEFT JOIN users ON comments.user_id=users.id $filter ORDER BY comments.create_at DESC 
LIMIT $offset, $perPage");

// Lấy dữ liệu tất cả người dùng
$allUsers = getRaw("SELECT id, fullname, email FROM users ORDER BY fullname");

?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="" method="get" style="padding-bottom: 15px">
                <div class="row">
                    <div class="col-3">
                        <select class="form-control" name="user_id">
                            <option value="0">Chọn người dùng</option>
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
                    <div class="col-4">
                        <input type="search" name="keyword" class="form-control" placeholder="Từ khóa tìm kiếm..."
                               value="<?php echo (!empty($keyword))?$keyword:false ?>">
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="0">Chọn trạng thái</option>
                                <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected' : false; ?>>
                                    Đã duyệt
                                </option>
                                <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected' : false; ?>>
                                    Chưa duyệt
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                    </div>
                </div>
                <input type="hidden" name="module" value="comments">
            </form>
            <?php
            getMsg($msg, $msg_type);
            ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th>Thông tin</th>
                    <th>Nội dung</th>
                    <th width="15%">Trạng thái</th>
                    <th width="10%">Thời gian</th>
                    <th width="10%">Bài viết</th>
                    <th width="10%">Sửa</th>
                    <th width="10%">Xoá</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($listComments)):
                    $count = 0; // Hiện thị số thứ tự
                    foreach ($listComments as $key => $item):
                        if (!empty($item['user_id'])) {
                            $item['name'] = $item['fullname'];
                            $item['email'] = $item['user_email'];
                            $commentLists[$key] = $item;
                        };
                        $count++;
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td>
                                - Họ tên: <?php echo $item['name']; ?> <br/>
                                - Email: <?php echo $item['email']; ?> <br/>
                                <?php
                                    if (!empty($item['website'])) {
                                        echo '- Website: '.$item['website'];
                                    }
                                ?>
                                <br/>
                                <?php
                                if (!empty($item['parent_id'])) {
                                    $commentData = getComment($item['parent_id']);
                                    if (!empty($commentData['name'])) {
                                        echo 'Trả lời: ' . $commentData['name'];
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                    echo getLimitText($item['content'], 20);
                                ?>
                            </td>
                            <td>
                                <?php
                                echo ($item['status']==0)?'<button class="btn btn-danger btn-sm">Chưa duyệt</button>':'<button class="btn btn-success btn-sm">Đã duyệt</button>';
                                if ($item['status']==0) {
                                    echo '<a href="'._WEB_HOST_ROOT_ADMIN.'/?module=comments&action=status&id='.$item['id'].'&status=1" class="btn btn-success btn-sm" style="margin-left: 10px">Duyệt</a>';
                                } else {
                                    echo '<a href="'._WEB_HOST_ROOT_ADMIN.'/?module=comments&action=status&id='.$item['id'].'&status=0" class="btn btn-danger btn-sm" style="margin-left: 10px">Bỏ duyệt</a>';
                                }

                                ?>
                            </td>
                            <td><?php echo getDateFormat($item['create_at'], 'd/m/Y H:i:s'); ?></td>
                            <td class="text-center">
                                <a href="<?php echo getLinkModule('blog', $item['blog_id']); ?>" target="_blank">
                                    <?php echo getLimitText($item['title'], 5); ?>
                                </a>
                            </td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('comments', 'edit', ['id'=>$item['id']]); ?>"
                                                       class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>  Sửa</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('comments', 'delete', ['id'=>$item['id']]); ?>"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa trang này?')"
                                                       class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>  Xóa</a></td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="8" class="text-center">Không có bình luận</td>
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
                <a class="page-link" href="'._WEB_HOST_ROOT_ADMIN.'?module=comments'.$queryString.'&page='.$prevPage.'" 
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
                            <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN.'?module=comments'.$queryString.'&page='.$index; ?>">
                                <?php echo $index; ?>
                            </a>
                        </li>
                    <?php }?>
                    <?php
                    if ($page < $maxPage) {
                        $nextPage = $page+1;
                        echo '<li class="page-item">
                <a class="page-link" href="'._WEB_HOST_ROOT_ADMIN.'?module=comments'.$queryString.'&page='.$nextPage.'" 
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

