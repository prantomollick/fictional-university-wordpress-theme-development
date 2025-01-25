<?php

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes() {
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'createLike'
    ));

    register_rest_route('university/v1', 'manageLike', array(
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'deleteLike'
    ));
}

function createLike($data) {
    if ( !is_user_logged_in() ) {
        return new WP_Error('onlyLoggedInUsers', 'You must be logged in to like a professor.', array('status' => 401));
    }

    $professorId = sanitize_text_field($data['professorId']);

    if ( get_post_type($professorId) !== 'professor' ) {
        return new WP_Error('invalidProfessorId', 'Invalid professor ID.', array('status' => 422));
    }

    $existQuery = new WP_Query(array(
        'author' => get_current_user_id(),
        'post_type' => 'like',
        'meta_query' => array(
            array(
                'key' => 'liked_professor_id',
                'compare' => '=',
                'value' => $professorId
            )
        )
    ));

    if( $existQuery->found_posts > 0 ) {
        return 'You have already liked this professor.';
    }

    return wp_insert_post(array(
        'post_type' => 'like',
        'post_status' => 'publish',
        'post_title' => '2nd PHP Test',
        'meta_input' => array(
            'liked_professor_id' => $professorId
        )
    ));
}

function deleteLike($data) {
    if ( !is_user_logged_in() ) {
        return new WP_Error('onlyLoggedInUsers', 'You must be logged in to like a professor.', array('status' => 401));
    }

    $likeId = sanitize_text_field($data['like']);

    if( get_post_type($likeId) !== 'like' ) {
        return new WP_Error('invalidLikeId', 'Invalid like ID.', array('status' => 422));
    }

    if ( get_current_user_id() != get_post_field('post_author', $likeId) ) {
        return new WP_Error('onlyLoggedInUsers', 'You do not have permission to delete this like.', array('status' => 401));
    }

    return wp_delete_post($likeId, true);
}