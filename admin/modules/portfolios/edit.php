<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => 'Cập nhật dự án'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
//Hide error php
ini_set('display_errors', 0);
error_reporting(0);

// Lấy dữ liệu cũ
$body = getBody('get');

if (!empty($body['id'])) {
    $portfolioId = $body['id'];

    // Kiểm tra $portfolioId có tồn tại trong database hay không?
    // Nếu tồn tại => lấy ra thông tin
    // Nếu không tồn tại => Chuyển hướng về trang lists
    $portfolioDetail = firstRaw("SELECT * FROM portfolios WHERE id=$portfolioId");

    // Truy vấn lấy thư viện ảnh
    $galleryDetailArr = getRaw("SELECT * FROM portfolio_images WHERE portfolio_id=$portfolioId");

    $galleryData = [];

    $galleryIdsArr = []; // Lưu trữ id gallery trong database

    if (!empty($galleryDetailArr)) {
        foreach ($galleryDetailArr as $gallery) {
            $galleryData[] = $gallery['image'];
            $galleryIdsArr[] = $gallery['id'];
        }
    }

    if (!empty($portfolioDetail)) {
        // Tồn tại
        // Gán giá trị $portfolioDetail vào flashData
        setFlashData('portfolioDetail', $portfolioDetail);

    } else {
        redirect('admin?module=portfolios');
    }
} else {
    redirect('admin?module=portfolios');
}

//Xử lý cập nhật dự án
if (isPost()){

    //Validate form
    $body = getBody(); //Lấy tất cả dữ liệu trong form

    $errors = []; //Mảng lưu trữ các lỗi

    //Validate tên dự án: Bắt buộc nhập

    if (empty(trim($body['name']))){
        $errors['name']['required'] = 'Tên dự án bắt buộc phải nhập';
    }

    //Validate slug: Bắt buộc nhập
    if (empty(trim($body['slug']))){
        $errors['slug']['required'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    //Validate nội dung: Bắt buộc phải nhập
    if (empty(trim($body['content']))){
        $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
    }

    //Validate link video: Bắt buộc phải nhập
    if (empty(trim($body['video']))){
        $errors['video']['required'] = 'Link video bắt buộc phải nhập';
    }

    //Validate danh mục dự án: Bắt buộc phải nhập
    if (empty(trim($body['portfolio_category_id']))){
        $errors['portfolio_category_id']['required'] = 'Danh mục dự án bắt buộc phải chọn';
    }

    //Validate ảnh đại diện: Bắt buộc phải nhập
    if (empty(trim($body['thumbnail']))){
        $errors['thumbnail']['required'] = 'Ảnh đại diện bắt buộc phải có';
    }

    // Validate ảnh dự án: Bắt buộc phải nhập
    if (!empty($body['gallery'])) {
        $galleryArr = $body['gallery'];
        foreach ($galleryArr as $key => $item) {
            if (empty(trim($item))) {
                $errors['gallery']['required'][$key] = 'Vui lòng chọn ảnh';
            }
        }
    }

    if (empty($galleryArr)) {
        $galleryArr = [];
    }

    //Kiểm tra mảng $errors
    if (empty($errors)) {
        //Không có lỗi xảy ra

        if (count($galleryArr) > count($galleryData)) {
            // insert những ảnh còn thiếu và update những ảnh thay thế
            if (!empty($galleryData)) {
                foreach ($galleryData as $key => $item) {
                    $dataImages = [
                        'image' => $galleryArr[$key],
                        'update_at' => date('Y-m-d H:i:s')
                    ];

                    // Update thư viện ảnh
//                    $condition = "image = '$item'";
                    $condition = "id=".$galleryIdsArr[$key];
                    update('portfolio_images', $dataImages, $condition);
                }
            } else {
                $key = -1;
            }

            for ($index = $key+1; $index < count($galleryArr); $index++) {
                $dataImages = [
                        'image' => $galleryArr[$index],
                    'portfolio_id' => $portfolioId,
                    'create_at' => date('Y-m-d H:i:s')
                ];
                // Insert ảnh còn thiếu
                insert('portfolio_images', $dataImages);
            }
        } elseif (count($galleryArr) < count($galleryData)) {
            foreach ($galleryArr as $key => $item) {
                $dataImages = [
                    'image' => $item,
                    'update_at' => date('Y-m-d H:i:s')
                ];

                // Update thư viện ảnh
//                $condition = "image = '".$galleryData[$key]."'";
                $condition = "id=".$galleryIdsArr[$key];
                update('portfolio_images', $dataImages, $condition);
            }

            if (is_null($key)) {
                $key = -1;
            }

            for ($index = $key+1; $index < count($galleryData); $index++) {
                // Delete ảnh thừa
//                $condition = "image = '".$galleryData[$index]."'";
                $condition = "id=".$galleryIdsArr[$index];
                delete('portfolio_images', $condition);
            }
        } else {
            foreach ($galleryData as $key => $item) {
                $dataImages = [
                    'image' => $galleryArr[$key],
                    'update_at' => date('Y-m-d H:i:s')
                ];

                // Update thư viện ảnh
                $condition = "image = '$item'";
                update('portfolio_images', $dataImages, $condition);
            }
        }

        $dataUpdate = [
            'name' => trim($body['name']),
            'slug' => trim($body['slug']),
            'content' => trim($body['content']),
            'video' => trim($body['video']),
            'description' => trim($body['description']),
            'thumbnail' => trim($body['thumbnail']),
            'portfolio_category_id' => trim($body['portfolio_category_id']),
            'update_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=$portfolioId";
        $updateStatus = update('portfolios', $dataUpdate, $condition);

        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật dự án thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        // Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('erros', $errors);
        setFlashData('old', $body);
    }

    redirect('admin?module=portfolios&action=edit&id='.$portfolioId);
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

if (empty($old) && !empty($portfolioDetail)) {
    $old = $portfolioDetail;

    $old['gallery'] = $galleryData;
}

// Truy vấn lấy danh sách danh mục dự án
$allCate = getRaw("SELECT * FROM portfolio_categories ORDER BY name");
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
                    <input type="text" class="form-control slug" name="name" placeholder="Tên dự án..." value="<?php echo old('name', $old); ?>"/>
                    <?php echo form_error('name', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Đường dẫn tĩnh</label>
                    <input type="text" class="form-control render-slug" name="slug" placeholder="Đường dẫn tĩnh..." value="<?php echo old('slug', $old); ?>"/>
                    <?php echo form_error('slug', $errors, '<span class="error">', '</span>'); ?>
                    <p class="render-link"><b>Link</b>: <span></span></p>
                </div>

                <div class="form-group">
                    <label for="">Mô tả ngắn</label>
                    <textarea name="description" class="form-control" placeholder="Mô tả ngắn..."><?php echo old('description', $old) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="">Nội dung</label>
                    <textarea name="content" class="form-control editor"><?php echo old('content', $old) ?></textarea>
                    <?php echo form_error('content', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Link video</label>
                    <input type="url" class="form-control" name="video" placeholder="Link video youtube" value="<?php echo old('video', $old) ?>">
                    <?php echo form_error('video', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Danh mục</label>
                    <select name="portfolio_category_id" class="form-control">
                        <option value="0">Chọn danh mục</option>
                        <?php
                        if (!empty($allCate)) {
                            foreach ($allCate as $item) {
                                ?>
                                <option value="<?php echo $item['id']; ?>" <?php
                                echo (old('portfolio_category_id', $old) == $item['id'])?'selected':false ?>><?php echo $item['name']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <?php echo form_error('portfolio_category_id', $errors, '<span class="error">', '</span>'); ?>
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

                <div class="form-group">
                    <label for="">Ảnh dự án</label>
                    <div class="gallery-images">
                        <?php
                        $oldGallery = old('gallery', $old);
                        if (!empty($oldGallery)) {
//                            $galleryErrors = $errors['gallery'];
                            foreach ($oldGallery as $key => $item) {
                                ?>
                                <div class="gallery-item">
                                    <div class="row">
                                        <div class="col-11">
                                            <div class="row ckfinder-group">
                                                <div class="col-10">
                                                    <input type="text" class="form-control image-render" name="gallery[]" placeholder="Đường dẫn ảnh..."
                                                           value="<?php echo (!empty($item))?$item:false; ?>"/>
                                                </div>
                                                <div class="col-2">
                                                    <button type="button" class="btn btn-success btn-block choose-image">Chọn ảnh</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <a href="" class="remove btn btn-danger btn-block"><i class="fa fa-times"></i></a>
                                        </div>
                                        <?php echo (!empty($galleryErrors['required'][$key]))?'<span class="error">'.$galleryErrors['required'][$key].'</span>':false; ?>
                                    </div>
                                </div><!-- End .gallery-item-->
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <p style="margin-top: 10px">
                        <a href="#" class="btn btn-warning btn-sm add-gallery">Thêm ảnh</a>
                    </p>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?php echo getLinkAdmin('portfolios', 'lists'); ?>" class="btn btn-success">Quay lại</a>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
