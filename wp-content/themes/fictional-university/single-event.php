<?php get_header(); ?>

<?php 
    while(have_posts()) : the_post();
    pageBanner(
        array(
            'title' => get_the_title(),
            'subtitle' => 'DON\'T FORGET TO REPLACE ME LATER'
        )
    );
?>
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event') ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> 
                    Events Home
                </a>
                <span class="metabox__main">
                 <?php the_title(); ?>
                </span>

            </p>
        </div>

        <div class="generic-content"><?php the_content(); ?></div>

        <?php
            $relatedPrograms = get_field('related_programs');
            if ( $relatedPrograms ) :
        ?>    
            <hr class="section-break">

            <h2 class="headline headline--medium"><?php esc_html_e('Related Programs(s)', 'fictional-university')?></h2>
            <ul class="link-list min-list">
                <?php foreach ($relatedPrograms as $program ) : ?>
                <li>
                    <a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
    <?php endwhile; ?>
    
<?php get_footer(); ?>

