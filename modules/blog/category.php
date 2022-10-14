<?php
if (!defined('_INCODE')) die('Access Denied...');

if (!empty(getBody()['id'])) {
    $id = getBody()['id'];

    $category = firstRaw("SELECT * FROM blog_categories WHERE  id=$id");

    if (empty($category)) {
        loadError();
    }
} else {
    loadError();
}

$data = [
    'pageTitle' => $category['name'],
    'itemParent' => '<li><a href="'._WEB_HOST_ROOT.'?module=blog">'.getOption('blog_title').'</a></li>'
];
layout('header', 'client', $data);

layout('breadcrumb', 'client', $data);

// Xử lý thuật toán phân trang
$allBlogNum = getRows('SELECT id FROM blog WHERE category_id='.$id);

$perPage = getOption('blog_per_page')?getOption('blog_per_page'):9;

$maxPage = ceil($allBlogNum/$perPage);

if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}

$offset = ($page-1)*$perPage;

$listBlog = getRaw("SELECT title, description, blog.id, thumbnail, view_count, blog.create_at, blog_categories.name as cate_name 
FROM blog INNER JOIN blog_categories ON 
    blog.category_id=blog_categories.id WHERE blog.category_id =$id ORDER BY blog.create_at DESC LIMIT $offset, $perPage");

?>
<!-- Blogs Area -->
<section class="blogs-main archives section">
    <div class="container">
        <?php
        if (!empty($listBlog)):
            ?>
            <div class="row">
                <?php foreach ($listBlog as $item): ?>
                    <div class="col-lg-4 col-md-6 col-12">
                        <!-- Single Blog -->
                        <div class="single-blog">
                            <div class="blog-head">
                                <img src="<?php echo $item['thumbnail']; ?>" alt="#">
                            </div>
                            <div class="blog-bottom">
                                <div class="blog-inner">
                                    <h4><a href="<?php echo _WEB_HOST_ROOT.'?module=blog&action=detail&id='.$item['id']; ?>"><?php echo $item['title']; ?></a></h4>
                                    <p><?php echo $item['description']; ?></p>
                                    <div class="meta">
                                        <span><i class="fa fa-bolt"></i><a href="#"><?php echo $item['cate_name']; ?></a></span>
                                        <span><i class="fa fa-calendar"></i><?php echo getDateFormat($item['create_at'], 'd/m/y'); ?></span>
                                        <span><i class="fa fa-eye"></i><a href="#"><?php echo $item['view_count']; ?></a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Blog -->
                    </div>
                <?php endforeach; ?>
            </div>
            <?php
            $begin = $page - 2;
            if ($begin < 1) {
                $begin = 1;
            }
            $end = $page + 2;
            if ($end > $maxPage) {
                $end = $maxPage;
            }

            if ($maxPage>1):
                ?>
                <div class="row">
                    <div class="col-12">
                        <!-- Start Pagination -->
                        <div class="pagination-main">
                            <ul class="pagination">
                                <?php
                                if ($page > 1) {
                                    $prevPage = $page - 1;
                                    echo '<li class="prev"><a href="'._WEB_HOST_ROOT.'?module=blog&action=category&page='.$prevPage.'"><i class="fa fa-angle-double-left"></i></a></li>';
                                }

                                for ($index = $begin; $index <= $end; $index++) {
                                    $classAtive = ($page==$index)?'active':false;
                                    echo '<li class="'.$classAtive.'"><a href="'._WEB_HOST_ROOT.'?module=blog&action=category&page='.$index.'">'.$index.'</a></li>';
                                }

                                if ($page < $maxPage) {
                                    $nextPage = $page + 1;
                                    echo '<li class="next"><a href="'._WEB_HOST_ROOT.'?module=blog&action=category&page='.$nextPage.'"><i class="fa fa-angle-double-right"></i></a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <!--/ End Pagination -->
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">Không có bài viết</div>
        <?php endif; ?>
    </div>
</section>
<!--/ End Blogs Area -->
<?php
layout('footer', 'client');
?>
