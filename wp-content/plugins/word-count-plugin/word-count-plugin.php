<?php

/*
    Plugin Name: Word Count Plugin
    Plugin URI: https://prantomollick.com
    Description: This is my Word Count Plugin
    Version: 1.0
    Author: Pranto Mollick
    Author URI: https://prantomollick.com
    License: GPLv2 or later
    Text Domain: word-count-plugin
    Domain Path: /languages
    Our First Unique Plugin is free software: you can redistribute it and/or modify
*/

if ( ! defined( 'ABSPATH' ) ) { // prevent direct access
    exit;
}

class WordCountAndTimePlugin {
    function __construct() {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_filter('the_content', array($this, 'ifWrap'));
        add_action('init', array($this, 'languages'));
    }

    function languages() {
        load_plugin_textdomain('word-count-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    function ifWrap($content) {
        if ( 
            is_main_query() AND is_single() AND
            ( 
            get_option('wcp_wordcount') == 1 
            OR get_option('wcp_charactercount') == 1 
            OR get_option('wcp_readtime') == 1) 
            ) {
            return $this->createHTML($content);
        }
        return $content;
    }

    function createHTML($content) {
        $headline = sanitize_text_field(get_option('wcp_headline', __('Post Statistics', 'word-count-plugin')));
        $showWordCount = filter_var(get_option('wcp_wordcount', '1'), FILTER_VALIDATE_BOOLEAN);
        $showCharacterCount = filter_var(get_option('wcp_charactercount', '1'), FILTER_VALIDATE_BOOLEAN);
        $showReadTime = filter_var(get_option('wcp_readtime', '1'), FILTER_VALIDATE_BOOLEAN);
        $displayLocation = sanitize_text_field(get_option('wcp_location', '0'));

        $html = '<h3>' . esc_html($headline) . '</h3><p>';

        //calculate word count only if needed
        if($showWordCount || $showReadTime) {
            $wordCount = str_word_count(strip_tags($content));
        }

        //wordcount section
        if($showWordCount) {
            $html .= esc_html__('This post has ', 'word-count-plugin') . intval($wordCount) . esc_html__(' words.', 'word-count-plugin') . '<br>';
        }

        //character count section
        if($showCharacterCount) {
            $html .= esc_html__('This post has ', 'word-count-plugin') . intval(strlen(strip_tags($content))) . esc_html__(' characters.', 'word-count-plugin') . '<br>';
        }


        //read time section
        if ($showReadTime) {
            $readTime = ceil($wordCount / 200); // Assuming average reading speed of 200 words per minute
            $html .= esc_html__('This post will take about ', 'word-count-plugin') . intval($readTime) . esc_html__(' minute(s) to read.', 'word-count-plugin');
        }

        $html .= '</p>';

        // Determine where to display the statistics
        if ( $displayLocation === '0') {
           return $html . $content; // Add at the beginning of the post
        } 

        return $content . $html; // Add at the end of the post
    }

    function adminPage(){
        add_options_page(
            'Word Count Settings',
            esc_html__('Word Count', 'word-count-plugin'),
            'manage_options',
            'word-count-settings-page',
            array($this, 'ourHTML')
        );
    }

    function settings() {
        add_settings_section(
            'wcp_first_section', 
            null,
             array(),
            'word-count-settings-page'
        );

        add_settings_field(
            'wcp_location',
            'Display Location',
            array($this, 'locationHTML'),
            'word-count-settings-page',
            'wcp_first_section'
        );
        register_setting(
        'wordcountplugin', 
        'wcp_location', 
        array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0')
        );

        add_settings_field(
            'wcp_headline',
            'Headline Text',
            array($this, 'headlineHTML'),
            'word-count-settings-page',
            'wcp_first_section'
        );
        register_setting(
            'wordcountplugin', 
            'wcp_headline', 
            array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post statistics')
        );

        add_settings_field(
            'wcp_wordcount',
            'Word Count',
            array($this, 'checkBoxHTML'),
            'word-count-settings-page',
            'wcp_first_section',
            array('theName' => 'wcp_wordcount')
        );
        register_setting(
            'wordcountplugin', 
            'wcp_wordcount', 
            array('sanitize_callback' => 'sanitize_text_field', 'default' => '1')
        );

        add_settings_field(
            'wcp_charactercount',
            'Character Count',
            array($this, 'checkBoxHTML'),
            'word-count-settings-page',
            'wcp_first_section',
            array('theName' => 'wcp_charactercount')
        );
        register_setting(
            'wordcountplugin', 
            'wcp_charactercount', 
            array('sanitize_callback' => 'sanitize_text_field', 'default' => '1')
        );

        add_settings_field(
            'wcp_readtime',
            'Read Time',
            array($this, 'checkBoxHTML'),
            'word-count-settings-page',
            'wcp_first_section', 
            array('theName' => 'wcp_readtime')
        );
        register_setting(
            'wordcountplugin', 
            'wcp_readtime', 
            array('sanitize_callback' => 'sanitize_text_field', 'default' => '1')
        );
    }

    function sanitizeLocation($input) {
        if ($input != '0' && $input != '1') {
            add_settings_error(
                'wcp_location',
                'wcp_location_error',
                'Display location must be either beginning or end',
                'error'
            );
            return get_option('wcp_location');
        }
        return $input;
    }

    function ourHTMl() {?>
        <div class="wrap">
            <h1>Word Count Settings</h1>
            <form action="options.php" method="post">
                <?php
                    settings_fields('wordcountplugin');
                    do_settings_sections('word-count-settings-page');
                    submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    function locationHTML() {?>
        <select name="wcp_location">
            <option value="0" <?php selected(get_option('wcp_location'), '0') ?> >Beginning of post</option>
            <option value="1" <?php selected(get_option('wcp_location'), '1') ?>>End of post</option>
        </select>
    <?php
    }

    function headlineHTML() {?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')); ?>">

    <?php
    }

    function checkBoxHTML($args) {?>
        <input type="checkbox" name="<?php echo $args['theName']; ?>" value="1" <?php checked(get_option($args['theName']), '1'); ?>>
    <?php
    }
}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();


