<?php
/*
    Plugin Name: Our Word Filter Plugin
    Plugin URI: https://prantomollick.com
    Description: Replace bad words with *** in your content
    Version: 1.0
    Author: Pranto Mollick
    Author URI: https://prantomollick.com
    License: GPLv2 or later
    Text Domain: word-filter-plugin
    Domain Path: /languages
    Our First Unique Plugin is free software: you can redistribute it and/or modify
*/

if ( ! defined( 'ABSPATH' ) ) { // prevent direct access
    exit;
}


class OurWordFilterPlugin {
    function __construct() {
        add_action('admin_menu', array($this, 'ourMenu'));
        add_action('admin_init', array($this, 'ourSettings'));

        if( get_option('plugin_words_to_filter') ) {
            add_filter('the_content', array($this, 'filterBardWords'));
        }
    }

    function ourSettings() {
        add_settings_section(
            'replacement-text-section',
            esc_html__('Replacement Text', 'word-filter-plugin'),
            array(),
            'word-filter-options'
        );

        register_setting(
            'replacementFields',
            'replacementText',
        );

        add_settings_field(
            'replacement-text-field',
            esc_html__('Filtered Text', 'word-filter-plugin'),
            array($this, 'replacementFieldHTML'),
            'word-filter-options',
            'replacement-text-section',
        );
    }

    function replacementFieldHTML() {?>
        <input type="text" name="replacementText" id="replacementText" value="<?php echo esc_attr(get_option('replacementText', '***')); ?>">
        <p class="description"><?php esc_html_e('Leave blank to simply remove the filtered words.'); ?></p>
    <?php
    }


    function filterBardWords($content) {
        $badWords = explode(',', get_option('plugin_words_to_filter'));
        $badWords = array_map('trim', $badWords);
        $badWords = array_map('strtolower', $badWords);
        $content = str_ireplace($badWords, esc_html(get_option('replacementText', '****')), $content);
        return $content;
    }


    function ourMenu() {
        //icon size 20x20
        $mainPageHook = add_menu_page(
            esc_html__('Word To Filter', 'word-filter-plugin'), 
            esc_html__('Word Filter', 'word-filter-plugin'), 
            'manage_options', 
            'our-word-filter',
            array($this, 'wordFilterPage'), 
            'data:image/svg+xml;base64, PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+Cg==',
        );

        add_submenu_page(
            'our-word-filter',
            esc_html__('Word To Filter', 'word-filter-plugin'),
            esc_html__('Words List', 'word-filter-plugin'),
            'manage_options',
            'our-word-filter',
            array($this, 'wordFilterPage'),
        );

        add_submenu_page(
            'our-word-filter',
            esc_html__('Word Filter Options', 'word-filter-plugin'),
            esc_html__('Options', 'word-filter-plugin'),
            'manage_options',
            'word-filter-options',
            array($this, 'optionsSubPage'),
        );

        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
    }

    function mainPageAssets() {
        wp_enqueue_style('our-word-filter-styles', plugin_dir_url(__FILE__) . 'styles.css');
    }

    function handleForm() {
        if ( isset($_POST['ourNonce']) && wp_verify_nonce($_POST['ourNonce'], 'saveFilterWords') AND current_user_can('manage_options')):
        update_option('plugin_words_to_filter', sanitize_text_field($_POST['plugin_words_to_filter']));
    ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('Settings updated successfully', 'word-filter-plugin'); ?></p>
        </div>

        <?php else:?>
            <div class="notice notice-error is-dismissible">
                <p><?php esc_html_e('Sorry, you do not have permission to perform that action.', 'word-filter-plugin'); ?></p>
            </div>
        <?php
        endif;
    }

    function wordFilterPage() {?>
        <div class="wrap">
            <h2><?php esc_html_e('Word Filter Plugin', 'word-filter-plugin'); ?></h2>
            <p><?php esc_html_e('This is a simple word filter plugin', 'word-filter-plugin'); ?></p>
            <?php if ( isset($_POST['justsubmitted']) && $_POST['justsubmitted'] == 'true') { $this->handleForm(); }?> 

            <form method="POST">
                <input type="hidden" name="justsubmitted" value="true">
                <?php wp_nonce_field('saveFilterWords', 'ourNonce'); ?>
                <label for="plugin_words_to_filter"><p><?php echo fic_kses('Enter a <strong>comma-separated</strong> list of words to filter from your site\'s content.')?></p></label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" id="plugin_words_to_filter" cols="30" rows="10" placeholder="bad, mean, awful, horrible"><?php echo esc_textarea(get_option('plugin_words_to_filter')); ?></textarea>
                </div>
                <button type="submit" id="submit" class="button button-primary"><?php esc_html_e('Save Changes', 'word-filter-plugin'); ?></button>
            </form>
        
        </div>
    <?php
    }

    function optionsSubPage() {?>
        <div class="wrap">
            <h2><?php esc_html_e('Word Filter Plugin Options', 'word-filter-plugin'); ?></h2>
            <p><?php esc_html_e('This is a simple word filter plugin', 'word-filter-plugin'); ?></p>

            <form action="options.php" method="POST">
                <?php 
                    settings_errors();
                    settings_fields('replacementFields'); 
                    do_settings_sections('word-filter-options'); 
                    submit_button();
                ?>
            </form>

        </div>
    <?php
    }

}

$ourWordFilterPlugin = new OurWordFilterPlugin();