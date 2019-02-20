<div class="blog-posts-all">
    <div class="wrap clearfix">
        <h1><?php echo $heading_title; ?></h1>
        <div class="blog-feed-each-all clearfix">
            <?php foreach ($all_news as $news) { ?>
            <div class="blog-feed-each clearfix">
                <a href="<?php echo $news['view']; ?>">
                    <div class="post-thumbnail" style="background: url(<?php echo $news['image']; ?>) center center / cover no-repeat transparent;"></div></a>
                <h4 class="orange"><?php echo $news['title']; ?></h4><?php echo $news['description']; ?><a href="<?php echo $news['view']; ?>">
                    <h5>Прочитать</h5></a>
            </div>
            <?php } ?>

        </div>
    </div>
</div>
