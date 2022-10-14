<?php
$title = getOption('home_blog_title');
$titleBg = getOption('home_blog_title_bg');
$desc = getOption('home_blog_desc');

// Truy vấn blog
$listBlog = getRaw("SELECT title, description, blog.id, thumbnail, view_count, blog.create_at, blog_categories.name as cate_name 
FROM blog INNER JOIN blog_categories ON 
    blog.category_id=blog_categories.id ORDER BY blog.create_at DESC LIMIT 10");
?>
<!-- Blogs Area -->
<section class="blogs-main section">
    <div class="container">
        <div class="row">
            <div class="col-12 wow fadeInUp">
                <div class="section-title">
                    <?php
                    echo !empty($titleBg)?'<span class="title-bg">'.$titleBg.'</span>':false ;
                    echo !empty($title)?'<h1>'.$title.'</h1>':false ;
                    echo !empty($desc)?'<p>'.$desc.'</p>':false ;
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row blog-slider">
                    <?php
                    if (!empty($listBlog)):
                        foreach ($listBlog as $item):
                    ?>
                    <div class="col-lg-4 col-12">
                        <!-- Single Blog -->
                        <div class="single-blog">
                            <div class="blog-head">
                                <img src="<?php echo $item['thumbnail']; ?>" alt="#">
                            </div>
                            <div class="blog-bottom">
                                <div class="blog-inner">
                                    <h4><a href="<?php echo getLinkModule('blog', $item['id']); ?>"><?php echo $item['title']; ?></a></h4>
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
                    <?php endforeach; endif; ?>
                    <div class="col-lg-4 col-12">
                        <!-- Single Blog -->
                        <div class="single-blog">
                            <div class="blog-head">
                                <img src="<?php echo _WEB_HOST_TEMPLATES; ?>/images/blogs/blog2.jpg" alt="#">
                            </div>
                            <div class="blog-bottom">
                                <div class="blog-inner">
                                    <h4><a href="blog-single.html">How to grow your business with blank table!</a></h4>
                                    <p>Maecenas sapien erat, porta non porttitor non, dignissim et enim. Aenean ac tincidunt tortor sedelon bond</p>
                                    <div class="meta">
                                        <span><i class="fa fa-bolt"></i><a href="#">Business</a></span>
                                        <span><i class="fa fa-calendar"></i>28 April, 2018</span>
                                        <span><i class="fa fa-eye"></i><a href="#">5m</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Blog -->
                    </div>
                    <div class="col-lg-4 col-12">
                        <!-- Single Blog -->
                        <div class="single-blog">
                            <div class="blog-head">
                                <img src="<?php echo _WEB_HOST_TEMPLATES; ?>/images/blogs/blog3.jpg" alt="#">
                            </div>
                            <div class="blog-bottom">
                                <div class="blog-inner">
                                    <h4><a href="blog-single.html">10 ways to improve your startup Business</a></h4>
                                    <p>Maecenas sapien erat, porta non porttitor non, dignissim et enim. Aenean ac tincidunt tortor sedelon bond</p>
                                    <div class="meta">
                                        <span><i class="fa fa-bolt"></i><a href="#">Brand</a></span>
                                        <span><i class="fa fa-calendar"></i>15 April, 2018</span>
                                        <span><i class="fa fa-eye"></i><a href="#">10m</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Blog -->
                    </div>
                    <div class="col-lg-4 col-12">
                        <!-- Single Blog -->
                        <div class="single-blog">
                            <div class="blog-head">
                                <img src="<?php echo _WEB_HOST_TEMPLATES; ?>/images/blogs/blog4.jpg" alt="#">
                            </div>
                            <div class="blog-bottom">
                                <div class="blog-inner">
                                    <h4><a href="blog-single.html">Recognizing the need is the primary</a></h4>
                                    <p>Maecenas sapien erat, porta non porttitor non, dignissim et enim. Aenean ac tincidunt tortor sedelon bond</p>
                                    <div class="meta">
                                        <span><i class="fa fa-bolt"></i><a href="#">Online</a></span>
                                        <span><i class="fa fa-calendar"></i>25 March, 2018</span>
                                        <span><i class="fa fa-eye"></i><a href="#">38k</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Blog -->
                    </div>
                    <div class="col-lg-4 col-12">
                        <!-- Single Blog -->
                        <div class="single-blog">
                            <div class="blog-head">
                                <img src="<?php echo _WEB_HOST_TEMPLATES; ?>/images/blogs/blog5.jpg" alt="#">
                            </div>
                            <div class="blog-bottom">
                                <div class="blog-inner">
                                    <h4><a href="blog-single.html">How to grow your business with blank table!</a></h4>
                                    <p>Maecenas sapien erat, porta non porttitor non, dignissim et enim. Aenean ac tincidunt tortor sedelon bond</p>
                                    <div class="meta">
                                        <span><i class="fa fa-bolt"></i><a href="#">Marketing</a></span>
                                        <span><i class="fa fa-calendar"></i>10 March, 2018</span>
                                        <span><i class="fa fa-eye"></i><a href="#">100k</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Blog -->
                    </div>
                    <div class="col-lg-4 col-12">
                        <!-- Single Blog -->
                        <div class="single-blog">
                            <div class="blog-head">
                                <img src="<?php echo _WEB_HOST_TEMPLATES; ?>/images/blogs/blog6.jpg" alt="#">
                            </div>
                            <div class="blog-bottom">
                                <div class="blog-inner">
                                    <h4><a href="blog-single.html">10 ways to improve your startup Business</a></h4>
                                    <p>Maecenas sapien erat, porta non porttitor non, dignissim et enim. Aenean ac tincidunt tortor sedelon bond</p>
                                    <div class="meta">
                                        <span><i class="fa fa-bolt"></i><a href="#">Website</a></span>
                                        <span><i class="fa fa-calendar"></i>21 February, 2018</span>
                                        <span><i class="fa fa-eye"></i><a href="#">320k</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Blog -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ End Blogs Area -->