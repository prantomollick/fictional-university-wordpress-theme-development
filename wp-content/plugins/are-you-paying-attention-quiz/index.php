<?php
/*
    Plugin Name: Are You Paying Attention Quiz
    Plugin URI: https://prantomollick.com
    Description: A simple quiz to test if you are paying attention
    Version: 1.0
    Author: Pranto Mollick
    Author URI: https://prantomollick.com
    License: GPLv2 or later
    Text Domain: are-you-paying-attention-quiz
    Domain Path: /languages
    Our First Unique Plugin is free software: you can redistribute it and/or modify
*/

if ( ! defined( 'ABSPATH' ) ) { // prevent direct access
    exit;
}

class AreYouPayingAttentionQuiz {
    function __construct() {
        add_action('init', array($this, 'adminAssets'));
    }

    function adminAssets() {
        wp_register_style('quizeditcss', plugin_dir_url(__FILE__) . 'build/index.css');
        wp_register_script('ournewblocktype', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-element', 'wp-editor'), '1.0', true);

        register_block_type('ourplugin/are-you-paying-attention-quiz', array(
            'editor_script' => 'ournewblocktype',
            'editor_style' => 'quizeditcss',
            'render_callback' => array($this, 'theHTML')
        ));
    }

    function theHTML($attributes) {
        if(!is_admin()) {
            wp_enqueue_script('attentionFrontend', plugin_dir_url(__FILE__) . 'build/frontend.js', array('wp-element'));
            wp_enqueue_style('attentionFrontendStyles', plugin_dir_url(__FILE__) . 'build/frontend.css');
        }
        ob_start(); 
    ?>
        
        <div class="paying-attention-update-me"></div>

    <?php return ob_get_clean();
    }

}

$areYouPayingAttentionQuiz = new AreYouPayingAttentionQuiz();