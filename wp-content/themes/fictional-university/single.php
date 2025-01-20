<?php get_header(); ?>

<?php 
    while(have_posts()) : the_post();
    pageBanner(array(
        'title' => get_the_title(),
        'subtitle' => 'DON\'T FORGET TO REPLACE ME LATER'
    ));
?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo site_url('/blog'); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> 
                    Blog Home
                </a>
                <span class="metabox__main">
                Posted by <?php if (function_exists('the_author_posts_link')) { the_author_posts_link(); } ?> 
                on <?php if (function_exists('the_time')) { the_time('n.j.y'); } ?> 
                in <?php if (function_exists('get_the_category_list')) { echo get_the_category_list(', '); } ?>
                </span>

            </p>
        </div>

        <div class="generic-content"><?php the_content(); ?></div>
    </div>

<?php 

endwhile; 
get_footer(); 

