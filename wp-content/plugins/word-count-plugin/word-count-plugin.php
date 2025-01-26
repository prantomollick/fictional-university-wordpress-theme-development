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
    Our First Unique Plugin is free software: you can redistribute it and/or modify
*/

class WordCountAndTimePlugin {
    function __construct() {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
    }

    function adminPage(){
        add_options_page(
            'Word Count Settings',
            'Word Count',
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
        array('sanitize_callback' => 'sanitize_text_field', 'default' => '0')
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


