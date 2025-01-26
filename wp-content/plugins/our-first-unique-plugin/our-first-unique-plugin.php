<?php

/*
    Plugin Name: Our First Unique Plugin
    Plugin URI: https://prantomollick.com
    Description: This is our first unique plugin.
    Version: 1.0
    Author: Pranto Mollick
    Author URI: https://prantomollick.com
    License: GPLv2 or later
    Text Domain: our-first-unique-plugin
    Our First Unique Plugin is free software: you can redistribute it and/or modify
*/

class WordCountAndTimePlugin {
    function __construct() {
        add_action('admin_menu', array($this, 'adminPage'));
    }

    function adminPage(){
        add_options_page(
            'Word Count Settings',
            'Word Count',
            'manage_options',
            'word-count-settings-page',
            array($this, 'ourHTML'),
        );
    }
    
    function ourHTMl() {?>
        <div class="wrap">
            <h1>Word Count Settings</h1>
        </div>
    <?php
    }
}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();


