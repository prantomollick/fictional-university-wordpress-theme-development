<?php

function university_post_types() {
    // Event Post Type
    register_post_type('event', array(
        'rewrite' => array('slug' => 'events'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar-alt',
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt')
    ));

    // Program Post Type
    register_post_type('program', array(
        'rewrite' => array('slug' => 'programs'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards',
        'show_in_rest' => true,
        'supports' => array('title', 'editor')
    ));

}


add_action('init', 'university_post_types');