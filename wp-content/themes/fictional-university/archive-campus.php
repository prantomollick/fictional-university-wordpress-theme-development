<?php get_header();
    pageBanner(array(
        'title' => __('Our Campuses', 'fictional-university'),
        'subtitle' => __('We have several conveniently located campuses', 'fictional-university')
    ));
?>

    <div class="container container--narrow page-section">
        <div class="acf-map">
            <?php while(have_posts()) { 
                the_post(); 
                $mapLocation = get_field('map_location');
            ?>
            <div data-lat="<?php echo $mapLocation['lat'] ?? '22.3838208' ?>" data-lng="<?php echo $mysql_compat['lng'] ?? '91.8224896'?>" class="marker">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php echo $mapLocation['address']; ?>
            </div>
            <?php } ?>
        </div>
    </div>

<?php get_footer(); ?>
