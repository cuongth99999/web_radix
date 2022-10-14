<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => 'Thêm Blog'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

//Lấy userId đăng nhập
$userId = isLogin()['user_id'];

//Xử lý thêm blog
if (isPost()){

    //Validate form
    $body = getBody(); //Lấy tất cả dữ liệu trong form

    $errors = []; //Mảng lưu trữ các lỗi

    //Validate tên blog: Bắt buộc nhập

    if (empty(trim($body['title']))){
        $errors['title']['required'] = 'Tiêu đề blog bắt buộc phải nhập';
    }

    //Validate slug: Bắt buộc nhập
    if (empty(trim($body['slug']))){
        $errors['slug']['required'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    //Validate nội dung: Bắt buộc phải nhập
    if (empty(trim($body['content']))){
        $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
    }

    //Validate chuyên mục: Bắt buộc phải chọn
    if (empty(trim($body['category_id']))){
        $errors['category_id']['required'] = 'Chuyên mục bắt buộc phải chọn';
    }

    //Validate thumbnail: Bắt buộc phải chọn
    if (empty(trim($body['thumbnail']))){
        $errors['thumbnail']['required'] = 'Ảnh đại diện bắt buộc phải có';
    }

    //Kiểm tra mảng $errors
    if (empty($errors)) {
        //Không có lỗi xảy ra

        $dataInsert = [
            'title' => trim($body['title']),
            'slug' => trim($body['slug']),
            'content' => trim($body['content']),
            'user_id' => $userId,
            'category_id' => trim($body['category_id']),
            'thumbnail' => trim($body['thumbnail']),
            'description' => trim($body['description']),
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('blog', $dataInsert);

        if ($insertStatus){
            setFlashData('msg', 'Thêm blog thành công');
            setFlashData('msg_type', 'success');

            redirect('admin?module=blog'); //Chuyển hướng qua blog danh sách

        }else{
            setFlashData('msg', 'Hệ thống đang gặp sự cố! Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');

            redirect('admin?module=blog&action=add'); //Load lại blog thêm blog
        }

    }else{

        //Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        redirect('admin?module=blog&action=add'); //Load lại blog
    }
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

// Lấy dữ liệu tất cả danh mục
$allCategories = getRaw("SELECT * FROM blog_categories ORDER BY name");

?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="" method="post">
                <?php
                getMsg($msg, $msgType);
                ?>
                <div class="form-group">
                    <label for="">Tiêu đề</label>
                    <input type="text" class="form-control slug" name="title" placeholder="Tiêu đề..." value="<?php echo old('title', $old); ?>"/>
                    <?php echo form_error('title', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Đường dẫn tĩnh</label>
                    <input type="text" class="form-control render-slug" name="slug" placeholder="Đường dẫn tĩnh..." value="<?php echo old('slug', $old); ?>"/>
                    <?php echo form_error('slug', $errors, '<span class="error">', '</span>'); ?>
                    <p class="render-link"><b>Link</b>: <span></span></p>
                </div>

                <div class="form-group">
                    <label for="">Mô tả</label>
                    <textarea name="description" class="form-control" placeholder="Mô tả ngắn..."><?php echo old('description', $old) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="">Nội dung</label>
                    <textarea name="content" class="form-control editor"><?php echo old('content', $old) ?></textarea>
                    <?php echo form_error('content', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Chuyên mục</label>
                    <select class="form-control" name="category_id">
                        <option value="0">Chọn chuyên mục</option>
                        <?php
                        if (!empty($allCategories)) {
                            foreach ($allCategories as $item) {
                                ?>
                                <option value="<?php echo $item['id']; ?>" <?php echo (old('category_id', $old)==$item['id'])?'selected':false; ?>
                                ><?php echo $item['name']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <?php echo form_error('category_id', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Ảnh đại diện</label>
                    <div class="row ckfinder-group">
                        <div class="col-10">
                            <input type="text" class="form-control image-render" name="thumbnail" placeholder="Đường dẫn ảnh..." value="<?php echo old('thumbnail', $old); ?>"/>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-success btn-block choose-image">Chọn ảnh</button>
                        </div>
                    </div>

                    <?php echo form_error('thumbnail', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <button type="submit" class="btn btn-primary">Thêm mới</button>
                <a href="<?php echo getLinkAdmin('blog', 'lists'); ?>" class="btn btn-success">Quay lại</a>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
