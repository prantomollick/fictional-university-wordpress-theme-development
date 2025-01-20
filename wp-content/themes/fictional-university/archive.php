<?php 

    get_header(); 

    $title = '';

    if( is_author() ) {
        $title = 'Posts By '. get_the_author();
    } else if( is_category() ) {
        $title = 'Posts in ' . single_cat_title('', false);
    } else {
        $title = get_the_archive_title();
    }

    pageBanner(array(
        'title' =>  $title,
        'subtitle' => get_the_archive_description()
    ));
?>


<div class="container container--narrow page-section">
    <?php while(have_posts()) { the_post(); ?>
        <div class="post-item">
            <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
            <div class="metabox">
                <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('n.j.y'); ?> in <?php echo get_the_category_list(', '); ?></p>
            </div>
            <div class="generic-content">
                <?php the_excerpt(); ?>
                <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
            </div>
        </div>
    <?php } ?>


    <?php echo paginate_links(); ?>
</div>



<?php get_footer(); ?>
