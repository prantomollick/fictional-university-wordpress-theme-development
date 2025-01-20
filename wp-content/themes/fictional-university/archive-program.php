<?php get_header();
    pageBanner(array(
        'title' => __('All Programs', 'fictional-university'),
        'subtitle' => __('There is something for everyone. Have a look around.', 'fictional-university')
    ));
?>




<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php while(have_posts()) { the_post(); ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php } ?>
    </ul>

    <?php echo paginate_links(); ?>
</div>



<?php get_footer(); ?>
