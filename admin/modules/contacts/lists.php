<?php
if (!defined('_INCODE')) die('Access Denied...');
$data = [
    'pageTitle' => 'Danh sách liên hệ'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

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
        $filter .= " $operator (message LIKE '%$keyword%' OR fullname LIKE '%$keyword%' OR email LIKE '%$keyword%')";
    }

    // Xử lý lọc theo phòng ban
    if (!empty($body['type_id'])) {
        $typeId = $body['type_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator contacts.type_id=$typeId";
    }
}


// Xử lý phân contacts
// Lấy số lượng bản ghi
$allContactNum = getRows("SELECT id FROM contacts $filter");

// 1. Xác định được số lượng bản ghi trên 1 contacts
$perPage = _PER_PAGE; // Mỗi contacts có 5 bản ghi

// 2. Tính tổng số contacts
$maxPage = ceil($allContactNum/$perPage);

// 3. Xử lý số contacts dựa vào phương thức GET
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

// Xử lý query string tìm kiếm với phân contacts
$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=contacts', '', $queryString);
    $queryString = str_replace('&page='.$page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&'.$queryString;
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

// Lấy dữ liệu contacts
$listContacts = getRaw("SELECT contacts.*, contact_type.name as type_name FROM contacts INNER JOIN contact_type ON contacts.type_id=contact_type.id
$filter ORDER BY contacts.create_at DESC LIMIT $offset, $perPage");

// Lấy dữ liệu tất cả phòng ban
$allContactTypes = getRaw("SELECT * FROM contact_type ORDER BY name");

?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="" method="get" style="padding-bottom: 15px">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="0">Chọn trạng thái</option>
                                <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected' : false; ?>>
                                    Đã xử lý
                                </option>
                                <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected' : false; ?>>
                                    Chưa xử lý
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <select class="form-control" name="type_id">
                            <option value="0">Chọn phòng ban</option>
                            <?php
                            if (!empty($allContactTypes)) {
                                foreach ($allContactTypes as $item) {
                                    ?>
                                    <option value="<?php echo $item['id']; ?>" <?php echo (!empty($typeId) && $typeId==$item['id'])?'selected':false; ?>
                                    ><?php echo $item['name']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-3">
                        <input type="search" name="keyword" class="form-control" placeholder="Nhập từ khóa tìm kiếm..."
                               value="<?php echo (!empty($keyword))?$keyword:false ?>">
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                    </div>
                </div>
                <input type="hidden" name="module" value="contacts">
            </form>
            <?php
            getMsg($msg, $msg_type);
            ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th>Thông tin</th>
                    <th width="20%">Nội dung liên hệ</th>
                    <th width="10%">Trạng thái</th>
                    <th width="15%">Ghi chú</th>
                    <th width="10%">Thời gian</th>
                    <th width="10%">Sửa</th>
                    <th width="10%">Xoá</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($listContacts)):
                    $count = 0; // Hiện thị số thứ tự
                    foreach ($listContacts as $item):
                        $count++;
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td>
                                Họ tên: <?php echo $item['fullname']; ?> <br>
                                Email: <?php echo $item['email']; ?> <br>
                                Phòng ban: <?php echo $item['type_name']; ?>
                            </td>
                            <td>
                                <?php echo $item['message']; ?>
                            </td>
                            <td class="text-center"><?php echo $item['status'] == 1 ? '<button type="button" class="btn btn-success btn-sm">
                                    Đã xử lý</button>' : '<button type="button" class="btn btn-warning btn-sm">
                                    Chưa xử lý</button>'; ?></td>
                            <td>
                                <?php echo $item['note']; ?>
                            </td>
                            <td><?php echo getDateFormat($item['create_at'], 'd/m/Y H:i:s'); ?></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('contacts', 'edit', ['id'=>$item['id']]); ?>"
                                                       class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>  Sửa</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('contacts', 'delete', ['id'=>$item['id']]); ?>"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa contacts này?')"
                                                       class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>  Xóa</a></td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="8" class="text-center">Không có liên hệ</td>
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
                <a class="page-link" href="'._WEB_HOST_ROOT_ADMIN.'?module=contacts'.$queryString.'&page='.$prevPage.'" 
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
                            <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN.'?module=contacts'.$queryString.'&page='.$index; ?>">
                                <?php echo $index; ?>
                            </a>
                        </li>
                    <?php }?>
                    <?php
                    if ($page < $maxPage) {
                        $nextPage = $page+1;
                        echo '<li class="page-item">
                <a class="page-link" href="'._WEB_HOST_ROOT_ADMIN.'?module=contacts'.$queryString.'&page='.$nextPage.'" 
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

