<?php

function pageBanner($args = array()) {
    $defaults = array(
        'title' => get_the_title(),
        'subtitle' => get_field('page_banner_subtitle'),
        'photo' =>  get_field('page_banner_background_image') ? 
                    get_field('page_banner_background_image')['sizes']['pageBanner'] : 
                    get_theme_file_uri('/assets/images/ocean.jpg')
    );

    // Merge the defaults with the arguments
    $args = array_merge($defaults, $args);
?>
    <div class="page-banner">
        <?php if( $args['photo'] ) : ?>    
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ?>)"></div>
        <?php endif; ?>

        <div class="page-banner__content container container--narrow">
            <?php if ( !empty($args['title'])) :?>
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <?php endif; ?>

            <?php if ( !empty($args['subtitle']) ) : ?>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle'] ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

<?php
}